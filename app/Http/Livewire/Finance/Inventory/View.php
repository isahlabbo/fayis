<?php

namespace App\Http\Livewire\Finance\Inventory;

use App\Models\InventoryItem;
use App\Models\InventoryCategory;
use Livewire\Component;

class View extends Component
{
    public $from_date;
    public $to_date;
    public $category_id;
    public $searchTerm;

    protected $listeners = ['itemSaved' => '$refresh'];

    public function deleteItem($id)
    {
        $item = InventoryItem::findOrFail($id);
        $item->delete();
        session()->flash('success', 'Inventory item deleted successfully.');
    }

    public function render()
    {
        $categories = InventoryCategory::orderBy('name')->get();

        $query = InventoryItem::with('category');

        if ($this->searchTerm) {
            $query->where('name', 'like', '%'.$this->searchTerm.'%')
                  ->orWhere('sku', 'like', '%'.$this->searchTerm.'%');
        }

        if ($this->category_id) {
            $query->where('inventory_category_id', $this->category_id);
        }

        if ($this->from_date) {
            $query->whereDate('created_at', '>=', $this->from_date);
        }

        if ($this->to_date) {
            $query->whereDate('created_at', '<=', $this->to_date);
        }

        $items = $query->orderBy('name')->get();

        return view('livewire.finance.inventory.view', [
            'items' => $items,
            'categories' => $categories,
        ]);
    }
}
