<?php

namespace App\Http\Livewire\Finance\Inventory;

use App\Models\InventoryItem;
use App\Models\InventoryRent;
use App\Models\Teacher;
use Livewire\Component;

class Rents extends Component
{
    public $teacher_id;
    public $item_id;
    public $quantity = 1;
    public $usage_date;
    public $notes;
    public $teachers;
    public $items;

    protected function rules()
    {
        return [
            'teacher_id' => 'required|exists:teachers,id',
            'item_id' => 'required|exists:inventory_items,id',
            'quantity' => 'required|integer|min:1',
            'usage_date' => 'required|date',
            'notes' => 'nullable|string|max:1000',
        ];
    }

    public function mount()
    {
        $this->teachers = Teacher::with('user')->orderBy('id')->get();
        $this->items = InventoryItem::with('category')->where('quantity', '>', 0)->orderBy('name')->get();
        $this->usage_date = now()->format('Y-m-d');
    }

    public function updatedItemId($value)
    {
        // no unit cost required for rent
    }

    public function saveRent()
    {
        $data = $this->validate();
        $item = InventoryItem::findOrFail($this->item_id);
        $totalCost = 0;

        InventoryRent::create([
            'inventory_item_id' => $data['item_id'],
            'teacher_id' => $data['teacher_id'],
            'quantity' => $data['quantity'],
            'unit_cost' => 0,
            'total_cost' => 0,
            'receipt_number' => null,
            'evidence' => null,
            'usage_date' => $data['usage_date'],
            'notes' => $data['notes'],
        ]);

        $item->decrement('quantity', $data['quantity']);

        session()->flash('success', 'Inventory rent recorded successfully.');

        $this->reset(['teacher_id', 'item_id', 'quantity', 'usage_date', 'notes']);
        $this->quantity = 1;
        $this->usage_date = now()->format('Y-m-d');
    }

    public function render()
    {
        return view('livewire.finance.inventory.rents', [
            'teachers' => $this->teachers,
            'items' => $this->items,
        ]);
    }
}
