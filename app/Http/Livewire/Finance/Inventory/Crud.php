<?php

namespace App\Http\Livewire\Finance\Inventory;

use App\Models\InventoryItem;
use App\Models\InventoryCategory;
use Illuminate\Support\Str;
use Livewire\Component;

class Crud extends Component
{
    public $itemId;
    public $inventory_category_id;
    public $name;
    public $description;
    public $categories;

    public $showForm = false;
    public $isEdit = false;

    protected $listeners = [
        'editInventoryItem' => 'editItem',
        'itemSaved' => '$refresh',
    ];

    protected $rules = [
        'inventory_category_id' => 'nullable|exists:inventory_categories,id',
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
    ];

    public function mount()
    {
        $this->categories = InventoryCategory::orderBy('name')->get();
    }

    public function render()
    {
        return view('livewire.finance.inventory.crud');
    }

    public function createItem()
    {
        $this->resetForm();
        $this->showForm = true;
        $this->isEdit = false;
    }

    public function editItem($id)
    {
        $item = InventoryItem::findOrFail($id);

        $this->itemId = $item->id;
        $this->inventory_category_id = $item->inventory_category_id;
        $this->name = $item->name;
        $this->description = $item->description;
        $this->showForm = true;
        $this->isEdit = true;
    }

    public function saveItem()
    {
        $rules = $this->rules;

        $data = $this->validate($rules);

        if ($this->isEdit) {
            $item = InventoryItem::findOrFail($this->itemId);
            $item->update([
                'inventory_category_id' => $this->inventory_category_id,
                'name' => $this->name,
                'description' => $this->description,
            ]);
            session()->flash('success', 'Inventory item updated successfully.');
        } else {
            $data['sku'] = strtoupper('ITEM-'.Str::random(6));
            $data['unit_cost'] = 0;
            $data['quantity'] = 0;
            InventoryItem::create([
                'inventory_category_id' => $this->inventory_category_id,
                'sku' => $data['sku'],
                'name' => $this->name,
                'description' => $this->description,
                'unit_cost' => $data['unit_cost'],
                'quantity' => $data['quantity'],
            ]);
            session()->flash('success', 'Inventory item created successfully.');
        }

        $this->resetForm();
        $this->showForm = false;
        $this->emit('itemSaved');
    }

    public function deleteItem($id)
    {
        $item = InventoryItem::findOrFail($id);
        $item->delete();
        session()->flash('success', 'Inventory item deleted successfully.');
        $this->emit('itemSaved');
    }

    public function resetForm()
    {
        $this->reset(['itemId', 'inventory_category_id', 'name', 'description']);
        $this->showForm = false;
        $this->isEdit = false;
    }
}
