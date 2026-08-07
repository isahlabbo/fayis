<?php

namespace App\Http\Livewire\Finance\Inventory;

use App\Models\InventoryItem;
use App\Models\InventoryCategory;
use App\Models\InventoryStock;
use Livewire\Component;

class Stock extends Component
{
    public $from_date;
    public $to_date;
    public $category_id;
    public $searchTerm;
    public $item_id;
    public $stock_quantity = 1;
    public $stock_unit_cost;
    public $received_date;
    public $notes;

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
            'received_date' => 'required|date',
            'notes' => 'nullable|string|max:1000',
        ];
    }

    public function updatedItemId($value)
    {
        $item = InventoryItem::find($value);
        $this->stock_unit_cost = $item ? $item->unit_cost : null;
    }

    public function saveStock()
    {
        $data = $this->validate();

        $item = InventoryItem::findOrFail($data['item_id']);

        InventoryStock::create([
            'inventory_item_id' => $data['item_id'],
            'quantity' => $data['stock_quantity'],
            'remaining_quantity' => $data['stock_quantity'],
            'unit_cost' => $data['stock_unit_cost'],
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
        $item->save();

        session()->flash('success', 'Stock batch added successfully.');

        $this->resetStockForm();
    }

    public function resetStockForm()
    {
        $this->reset(['item_id', 'stock_quantity', 'stock_unit_cost', 'received_date', 'notes']);
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
