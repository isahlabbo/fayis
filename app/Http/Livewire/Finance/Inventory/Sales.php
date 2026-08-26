<?php

namespace App\Http\Livewire\Finance\Inventory;

use App\Models\InventoryItem;
use App\Models\InventorySale;
use App\Models\InventorySaleItem;
use App\Models\InventoryStock;
use App\Models\FinanceActivityLog;
use App\Models\Section;
use App\Models\SectionClass;
use App\Models\SectionClassStudent;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class Sales extends Component
{
    public function boot() { abort_unless(Auth::check() && (Auth::user()->role === 'finance_officer' || Auth::user()->hasPermission('manage-sales')), 403); }
    public $section_id;
    public $class_id;
    public $student_id;
    public $selectedItems = [];
    public $itemQuantities = [];
    public $itemUnitCosts = [];
    public $payment_method = 'Cash';
    public $usage_date;
    public $sections;
    public $classes = [];
    public $students = [];
    public $items;
    public $from_date;
    public $to_date;
    public $searchTerm = '';
    public $saleId;

    protected function rules()
    {
        return [
            'section_id' => 'required|exists:sections,id',
            'class_id' => 'required|exists:section_classes,id',
            'student_id' => 'required|exists:section_class_students,id',
            'selectedItems' => 'required|array|min:1',
            'selectedItems.*' => 'required|exists:inventory_items,id',
            'itemQuantities' => 'required|array',
            'itemQuantities.*' => 'required|integer|min:1',
            'payment_method' => 'required|in:Cash,Transfer,POS,Cheque',
            'usage_date' => 'required|date',
        ];
    }

    public function mount()
    {
        $this->sections = Section::orderBy('name')->get();
        $this->items = InventoryItem::with('category')->where('quantity', '>', 0)->orderBy('name')->get();
        $this->usage_date = now()->format('Y-m-d');
        $this->from_date = null;
        $this->to_date = null;
        $this->searchTerm = '';
    }

    public function updatedSectionId($value)
    {
        $this->classes = SectionClass::where('section_id', $value)->pluck('name', 'id');
        $this->students = [];
        $this->class_id = null;
        $this->student_id = null;
    }

    public function updatedClassId($value)
    {
        $this->students = SectionClassStudent::where('section_class_id', $value)
            ->where('status', 'Active')
            ->with('student')
            ->get()
            ->map(fn($sectionClassStudent) => ['id' => $sectionClassStudent->id, 'name' => $sectionClassStudent->student->name]);
        $this->student_id = null;
    }

    public function updatedSelectedItems($value)
    {
        foreach ($value as $itemId) {
            if (!isset($this->itemQuantities[$itemId])) {
                $this->itemQuantities[$itemId] = 1;
            }
            if (!isset($this->itemUnitCosts[$itemId])) {
                $quote=collect($this->fifoQuote($itemId,1));
                $this->itemUnitCosts[$itemId]=$quote->sum('amount');
            }
        }

        $this->itemQuantities = array_intersect_key($this->itemQuantities, array_flip($value));
        $this->itemUnitCosts = array_intersect_key($this->itemUnitCosts, array_flip($value));
    }

    public function getSalesQueryProperty()
    {
        return InventorySale::with(['saleItems.item.category', 'student.student'])
            ->when($this->from_date, fn($query, $value) => $query->whereDate('usage_date', '>=', $value))
            ->when($this->to_date, fn($query, $value) => $query->whereDate('usage_date', '<=', $value))
            ->when($this->searchTerm, function ($query, $value) {
                $query->where(function ($query) use ($value) {
                    $query->where('payment_method', 'like', '%'.$value.'%')
                        ->orWhereHas('student.student', fn($query) => $query->where('name', 'like', '%'.$value.'%')->orWhere('admission_no', 'like', '%'.$value.'%'))
                        ->orWhereHas('saleItems.item', fn($query) => $query->where('name', 'like', '%'.$value.'%')->orWhere('sku', 'like', '%'.$value.'%'));
                });
            });
    }

    public function getTotalSaleAmountProperty()
    {
        $total = 0;
        foreach ($this->selectedItems as $itemId) {
            $quantity = $this->itemQuantities[$itemId] ?? 0;
            $total += collect($this->fifoQuote($itemId,$quantity))->sum('amount');
        }

        return $total;
    }

    public function updatedItemQuantities(){foreach($this->selectedItems as $itemId){$quantity=(int)($this->itemQuantities[$itemId]??0);$quote=collect($this->fifoQuote($itemId,$quantity));$this->itemUnitCosts[$itemId]=$quantity>0?$quote->sum('amount')/$quantity:0;}}

    private function fifoQuote($itemId,$quantity,$lock=false)
    {
        $query=InventoryStock::where('inventory_item_id',$itemId)->where('remaining_quantity','>',0)->orderByRaw('received_date IS NULL')->orderBy('received_date')->orderBy('id');if($lock)$query->lockForUpdate();
        $remaining=(int)$quantity;$lines=[];foreach($query->get() as $batch){if($remaining<=0)break;$take=min($remaining,$batch->remaining_quantity);$sell=(float)$batch->unit_selling_price;$lines[]=['stock'=>$batch,'quantity'=>$take,'cost_price'=>(float)$batch->unit_cost,'selling_price'=>$sell,'amount'=>$take*$sell];$remaining-=$take;}
        return $remaining>0?[]:$lines;
    }

    public function saveSale()
    {
        $data = $this->validate();

        $sale=DB::transaction(function()use($data){$editing=(bool)$this->saleId;$sale=$editing?InventorySale::with('saleItems')->lockForUpdate()->findOrFail($this->saleId):new InventorySale();if($editing){$oldProfit=$sale->saleItems->sum('profit');FinanceActivityLog::record('sales_profit',$sale,'Previous profit reversed while editing sale #'.$sale->id,-$oldProfit);$this->restoreSaleStock($sale);}$sale->fill(['section_class_student_id'=>$data['student_id'],'total_cost'=>0,'payment_method'=>$data['payment_method'],'usage_date'=>$data['usage_date']]);$sale->save();$total=0;
            foreach($data['selectedItems'] as $itemId){$quantity=(int)$data['itemQuantities'][$itemId];$item=InventoryItem::lockForUpdate()->findOrFail($itemId);$lines=$this->fifoQuote($itemId,$quantity,true);if(!$lines)throw ValidationException::withMessages(['itemQuantities.'.$itemId=>'Insufficient FIFO stock batches for '.$item->name.'.']);foreach($lines as $line){InventorySaleItem::create(['inventory_sale_id'=>$sale->id,'inventory_item_id'=>$itemId,'inventory_stock_id'=>$line['stock']->id,'quantity'=>$line['quantity'],'unit_cost'=>$line['selling_price'],'cost_price'=>$line['cost_price'],'amount'=>$line['amount']]);$line['stock']->decrement('remaining_quantity',$line['quantity']);$total+=$line['amount'];}$item->decrement('quantity',$quantity);}
            $sale->update(['total_cost'=>$total]);$profit=$sale->saleItems()->get()->sum('profit');FinanceActivityLog::record($editing?'sale_updated':'inventory_sale',$sale,$editing?'Inventory sale updated':'FIFO inventory sale recorded',$total,['items'=>count($data['selectedItems']),'profit'=>$profit]);FinanceActivityLog::record('sales_profit',$sale,'Gross profit from sale #'.$sale->id,$profit,['sale_total'=>$total]);return $sale;});

        session()->flash('success', 'Inventory sale recorded successfully.');

        return redirect()->route('finance.inventory.sales.receipt', ['saleId' => $sale->id]);
    }

    public function editSale($id)
    {
        $sale=InventorySale::with(['saleItems','student.sectionClass'])->findOrFail($id);$this->saleId=$sale->id;$this->section_id=$sale->student->sectionClass->section_id;$this->classes=SectionClass::where('section_id',$this->section_id)->pluck('name','id');$this->class_id=$sale->student->section_class_id;$this->students=SectionClassStudent::where('section_class_id',$this->class_id)->where('status','Active')->with('student')->get()->map(fn($r)=>['id'=>$r->id,'name'=>$r->student->name]);$this->student_id=$sale->section_class_student_id;$this->selectedItems=$sale->saleItems->pluck('inventory_item_id')->unique()->map(fn($id)=>(string)$id)->values()->all();$this->itemQuantities=$sale->saleItems->groupBy('inventory_item_id')->map->sum('quantity')->all();$this->itemUnitCosts=$sale->saleItems->groupBy('inventory_item_id')->map(fn($lines)=>$lines->sum('amount')/$lines->sum('quantity'))->all();$this->payment_method=$sale->payment_method;$this->usage_date=optional($sale->usage_date)->format('Y-m-d');$this->resetValidation();
    }

    public function cancelEdit(){ $this->saleId=null;$this->reset(['section_id','class_id','student_id','selectedItems','itemQuantities','itemUnitCosts']);$this->payment_method='Cash';$this->usage_date=now()->format('Y-m-d'); }

    public function deleteSale($id)
    {
        DB::transaction(function()use($id){$sale=InventorySale::with('saleItems')->lockForUpdate()->findOrFail($id);$total=(float)$sale->total_cost;$profit=$sale->saleItems->sum('profit');$snapshot=$sale->saleItems->map(fn($line)=>['item_id'=>$line->inventory_item_id,'stock_id'=>$line->inventory_stock_id,'quantity'=>$line->quantity,'cost'=>$line->cost_price,'price'=>$line->unit_cost])->all();$this->restoreSaleStock($sale);FinanceActivityLog::record('sale_deleted',$sale,'Sale #'.$sale->id.' deleted',-$total,['profit_reversed'=>-$profit,'lines'=>$snapshot]);FinanceActivityLog::record('sales_profit',$sale,'Profit reversed for deleted sale #'.$sale->id,-$profit);$sale->delete();});session()->flash('success','Sale deleted and FIFO stock restored.');
    }

    private function restoreSaleStock($sale)
    {
        $lines=$sale->saleItems()->lockForUpdate()->get();foreach($lines as $line){if($line->inventory_stock_id)InventoryStock::whereKey($line->inventory_stock_id)->increment('remaining_quantity',$line->quantity);InventoryItem::whereKey($line->inventory_item_id)->increment('quantity',$line->quantity);}$sale->saleItems()->delete();
    }

    public function render()
    {
        $selectedItems = InventoryItem::whereIn('id', $this->selectedItems)->get();
        $sales = $this->salesQuery->get();

        return view('livewire.finance.inventory.sales', [
            'sections' => $this->sections,
            'classes' => $this->classes,
            'students' => $this->students,
            'items' => $this->items,
            'selectedItemModels' => $selectedItems,
            'totalSaleAmount' => $this->totalSaleAmount,
            'sales' => $sales,
            'totalSalesCount' => $sales->count(),
            'totalRevenue' => $sales->sum('total_cost'),
            'totalItemsSold' => $sales->sum(fn($sale) => $sale->saleItems->sum('quantity')),
            'totalProfit' => $sales->sum(fn($sale) => $sale->saleItems->sum('profit')),
        ]);
    }
}
