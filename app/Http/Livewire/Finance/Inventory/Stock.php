<?php

namespace App\Http\Livewire\Finance\Inventory;

use App\Models\InventoryItem;
use App\Models\InventoryCategory;
use App\Models\InventoryStock;
use App\Models\FinanceActivityLog;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Stock extends Component
{
    public $from_date;
    public $to_date;
    public $category_id;
    public $searchTerm;
    public $item_id;
    public $stock_quantity = 1;
    public $stock_unit_cost;
    public $stock_selling_price;
    public $received_date;
    public $notes;
    public $stockId;

    public function boot(){ abort_unless(Auth::check() && Auth::user()->hasPermission('manage-inventory'),403); }

    public function mount()
    {
        $this->received_date = now()->format('Y-m-d');
    }

    protected function rules()
    {
        return [
            'item_id' => 'required|exists:inventory_items,id',
            'stock_quantity' => 'required|integer|min:1',
            'stock_unit_cost' => 'required|numeric|min:0',
            'stock_selling_price' => 'required|numeric|gte:stock_unit_cost',
            'received_date' => 'required|date',
            'notes' => 'nullable|string|max:1000',
        ];
    }

    public function updatedItemId($value)
    {
        $item = InventoryItem::find($value);
        $this->stock_unit_cost = $item ? $item->unit_cost : null;
        $this->stock_selling_price = $item ? $item->selling_price : null;
    }

    public function saveStock()
    {
        $data = $this->validate();

        if($this->stockId){$this->updateStock($data);return;}

        $item = InventoryItem::findOrFail($data['item_id']);

        $stock = InventoryStock::create([
            'inventory_item_id' => $data['item_id'],
            'quantity' => $data['stock_quantity'],
            'remaining_quantity' => $data['stock_quantity'],
            'unit_cost' => $data['stock_unit_cost'],
            'unit_selling_price' => $data['stock_selling_price'],
            'received_date' => $data['received_date'],
            'notes' => $data['notes'],
        ]);

        $oldQuantity = $item->quantity;
        $oldUnitCost = $item->unit_cost;
        $newQuantity = $oldQuantity + $data['stock_quantity'];

        $item->quantity = $newQuantity;
        $item->unit_cost = $newQuantity > 0
            ? (($oldQuantity * $oldUnitCost) + ($data['stock_quantity'] * $data['stock_unit_cost'])) / $newQuantity
            : $data['stock_unit_cost'];
        $item->selling_price = $data['stock_selling_price'];
        $item->save();
        FinanceActivityLog::record('stock_received', $stock, 'Stock received: '.$item->name, $data['stock_quantity'] * $data['stock_unit_cost'], ['quantity'=>$data['stock_quantity']]);

        session()->flash('success', 'Stock batch added successfully.');

        $this->resetStockForm();
    }

    public function editStock($id)
    {
        $stock=InventoryStock::findOrFail($id);$this->stockId=$stock->id;$this->item_id=$stock->inventory_item_id;$this->stock_quantity=$stock->quantity;$this->stock_unit_cost=$stock->unit_cost;$this->stock_selling_price=$stock->unit_selling_price;$this->received_date=optional($stock->received_date)->format('Y-m-d');$this->notes=$stock->notes;$this->resetValidation();
    }

    private function updateStock($data)
    {
        DB::transaction(function()use($data){$stock=InventoryStock::lockForUpdate()->findOrFail($this->stockId);$oldItemId=$stock->inventory_item_id;$sold=$stock->quantity-$stock->remaining_quantity;if($data['stock_quantity']<$sold){$this->addError('stock_quantity','Quantity cannot be less than '.$sold.' units already sold from this batch.');return;}if($sold>0&&(int)$data['item_id']!==(int)$oldItemId){$this->addError('item_id','A batch already used in sales cannot be moved to another item.');return;}$old=['quantity'=>$stock->quantity,'unit_cost'=>$stock->unit_cost,'unit_selling_price'=>$stock->unit_selling_price];$stock->update(['inventory_item_id'=>$data['item_id'],'quantity'=>$data['stock_quantity'],'remaining_quantity'=>$data['stock_quantity']-$sold,'unit_cost'=>$data['stock_unit_cost'],'unit_selling_price'=>$data['stock_selling_price'],'received_date'=>$data['received_date'],'notes'=>$data['notes']]);$this->syncItemFromBatches($oldItemId);if((int)$oldItemId!==(int)$data['item_id'])$this->syncItemFromBatches($data['item_id']);FinanceActivityLog::record('stock_updated',$stock,'Stock batch updated',0,['before'=>$old]);});
        if($this->getErrorBag()->isNotEmpty())return;$this->resetStockForm();session()->flash('success','Stock batch updated successfully.');
    }

    public function deleteStock($id)
    {
        DB::transaction(function()use($id){$stock=InventoryStock::lockForUpdate()->findOrFail($id);if($stock->remaining_quantity!=$stock->quantity||$stock->saleItems()->exists()){$this->addError('stock_delete','A batch already used in sales cannot be deleted.');return;}$itemId=$stock->inventory_item_id;FinanceActivityLog::record('stock_deleted',$stock,'Unused stock batch deleted',0,['quantity'=>$stock->quantity]);$stock->delete();$this->syncItemFromBatches($itemId);});
        if($this->getErrorBag()->isEmpty())session()->flash('success','Stock batch deleted.');
    }

    private function syncItemFromBatches($itemId)
    {
        $item=InventoryItem::lockForUpdate()->findOrFail($itemId);$batches=InventoryStock::where('inventory_item_id',$itemId)->where('remaining_quantity','>',0)->orderByRaw('received_date IS NULL')->orderBy('received_date')->orderBy('id')->get();$quantity=$batches->sum('remaining_quantity');$item->quantity=$quantity;$item->unit_cost=$quantity>0?$batches->sum(fn($b)=>$b->remaining_quantity*$b->unit_cost)/$quantity:0;$item->selling_price=optional($batches->first())->unit_selling_price?:0;$item->save();
    }

    public function resetStockForm()
    {
        $this->reset(['stockId','item_id', 'stock_quantity', 'stock_unit_cost', 'stock_selling_price', 'received_date', 'notes']);
        $this->received_date = now()->format('Y-m-d');
        $this->stock_quantity = 1;
    }

    public function loadStocks()
    {
        return InventoryStock::with(['item.category'])
            ->when($this->searchTerm, fn($query) => $query->whereHas('item', fn($query) => $query->where('name', 'like', '%'.$this->searchTerm.'%')
                ->orWhere('sku', 'like', '%'.$this->searchTerm.'%')))
            ->when($this->category_id, fn($query) => $query->whereHas('item', fn($query) => $query->where('inventory_category_id', $this->category_id)))
            ->when($this->from_date, fn($query) => $query->whereDate('received_date', '>=', $this->from_date))
            ->when($this->to_date, fn($query) => $query->whereDate('received_date', '<=', $this->to_date))
            ->orderByDesc('received_date')
            ->get();
    }

    public function render()
    {
        $categories = InventoryCategory::orderBy('name')->get();

        $items = InventoryItem::with('category')
            ->when($this->searchTerm, fn($query) => $query->where('name', 'like', '%'.$this->searchTerm.'%')
                ->orWhere('sku', 'like', '%'.$this->searchTerm.'%'))
            ->when($this->category_id, fn($query) => $query->where('inventory_category_id', $this->category_id))
            ->when($this->from_date, fn($query) => $query->whereDate('updated_at', '>=', $this->from_date))
            ->when($this->to_date, fn($query) => $query->whereDate('updated_at', '<=', $this->to_date))
            ->orderBy('name')
            ->get();

        $stocks = $this->loadStocks();

        return view('livewire.finance.inventory.stock', [
            'items' => $items,
            'categories' => $categories,
            'stocks' => $stocks,
        ]);
    }
}
