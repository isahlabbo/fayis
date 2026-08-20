<?php

namespace App\Http\Livewire\Finance\Inventory;

use App\Models\InventoryItem;
use App\Models\InventorySale;
use App\Models\InventorySaleItem;
use App\Models\Section;
use App\Models\SectionClass;
use App\Models\SectionClassStudent;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Sales extends Component
{
    public function boot() { abort_unless(Auth::check() && (Auth::user()->role === 'finance_officer' || Auth::user()->hasPermission('manage-sales')), 403); }
    public $section_id;
    public $class_id;
    public $student_id;
    public $selectedItems = [];
    public $itemQuantities = [];
    public $itemUnitCosts = [];
    public $evidence;
    public $usage_date;
    public $notes;
    public $sections;
    public $classes = [];
    public $students = [];
    public $items;
    public $from_date;
    public $to_date;
    public $searchTerm = '';

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
            'itemUnitCosts' => 'required|array',
            'itemUnitCosts.*' => 'required|numeric|min:0',
            'evidence' => 'nullable|string|max:1000',
            'usage_date' => 'required|date',
            'notes' => 'nullable|string|max:1000',
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
                $item = InventoryItem::find($itemId);
                $this->itemUnitCosts[$itemId] = $item ? $item->unit_cost : 0;
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
                    $query->where('evidence', 'like', '%'.$value.'%')
                        ->orWhere('notes', 'like', '%'.$value.'%')
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
            $unitCost = $this->itemUnitCosts[$itemId] ?? 0;
            $total += $quantity * $unitCost;
        }

        return $total;
    }

    public function saveSale()
    {
        $data = $this->validate();

        $totalCost = 0;
        foreach ($data['selectedItems'] as $itemId) {
            $quantity = $data['itemQuantities'][$itemId] ?? 0;
            $unitCost = $data['itemUnitCosts'][$itemId] ?? 0;
            $totalCost += $quantity * $unitCost;
        }

        $sale = InventorySale::create([
            'section_class_student_id' => $data['student_id'],
            'total_cost' => $totalCost,
            'evidence' => $data['evidence'],
            'usage_date' => $data['usage_date'],
            'notes' => $data['notes'],
        ]);

        foreach ($data['selectedItems'] as $itemId) {
            $quantity = $data['itemQuantities'][$itemId] ?? 0;
            $unitCost = $data['itemUnitCosts'][$itemId] ?? 0;

            InventorySaleItem::create([
                'inventory_sale_id' => $sale->id,
                'inventory_item_id' => $itemId,
                'quantity' => $quantity,
                'unit_cost' => $unitCost,
                'amount' => $quantity * $unitCost,
            ]);

            $item = InventoryItem::findOrFail($itemId);
            $item->decrement('quantity', $quantity);
        }

        session()->flash('success', 'Inventory sale recorded successfully.');

        return redirect()->route('finance.inventory.sales.receipt', ['saleId' => $sale->id]);
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
        ]);
    }
}
