<?php

namespace App\Http\Livewire\Finance\Inventory;

use App\Models\InventoryTransaction;
use App\Models\InventoryCategory;
use Livewire\Component;

class Reconcile extends Component
{
    public $from_date;
    public $to_date;
    public $searchTerm;

    public function render()
    {
        $categories = InventoryCategory::orderBy('name')->get();

        $transactions = InventoryTransaction::with('item.category')
            ->when($this->searchTerm, fn($query) => $query->whereHas('item', fn($item) => $item->where('name', 'like', '%'.$this->searchTerm.'%')
                ->orWhere('sku', 'like', '%'.$this->searchTerm.'%')))
            ->when($this->from_date, fn($query) => $query->whereDate('transaction_date', '>=', $this->from_date))
            ->when($this->to_date, fn($query) => $query->whereDate('transaction_date', '<=', $this->to_date))
            ->orderByDesc('transaction_date')
            ->get();

        return view('livewire.finance.inventory.reconcile', [
            'transactions' => $transactions,
            'categories' => $categories,
        ]);
    }
}
