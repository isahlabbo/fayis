<?php

namespace App\Http\Livewire\Finance\Sanitary;

use App\Models\SanitaryItem;
use App\Models\SanitaryStock;
use App\Services\Finance\SanitaryMaterials;

class Stock extends Screen
{
    public $stockId, $sanitary_item_id = '', $quantity = 1, $unit_cost = 0, $received_date;
    public $supplier = '', $reference = '', $notes = '';

    public function mount() { $this->received_date = now()->toDateString(); }

    public function saveStock()
    {
        app(SanitaryMaterials::class)->saveStock($this->only([
            'sanitary_item_id', 'quantity', 'unit_cost', 'received_date', 'supplier', 'reference', 'notes',
        ]), $this->stockId);
        $this->resetForm();
        session()->flash('success', 'Sanitary stock saved.');
    }

    public function editStock($id)
    {
        $this->authorizeManagement();
        $stock = SanitaryStock::findOrFail($id);
        $this->stockId = $stock->id;
        foreach (['sanitary_item_id', 'quantity', 'unit_cost', 'supplier', 'reference', 'notes'] as $field) $this->$field = $stock->$field;
        $this->received_date = $stock->received_date->toDateString();
        $this->resetValidation();
    }

    public function deleteStock($id)
    {
        app(SanitaryMaterials::class)->deleteStock($id);
        if ((int) $this->stockId === (int) $id) $this->resetForm();
        $this->resetPage();
        session()->flash('success', 'Unused sanitary stock deleted.');
    }

    public function resetForm()
    {
        $this->reset(['stockId', 'sanitary_item_id', 'quantity', 'unit_cost', 'received_date', 'supplier', 'reference', 'notes']);
        $this->received_date = now()->toDateString();
        $this->resetValidation();
    }

    public function render()
    {
        $query = SanitaryStock::with('item')
            ->when($this->filterItem, fn($q) => $q->where('sanitary_item_id', $this->filterItem))
            ->when($this->search, fn($q) => $q->whereHas('item', fn($q) => $q->where('name', 'like', '%'.$this->search.'%')->orWhere('sku', 'like', '%'.$this->search.'%')))
            ->when($this->fromDate, fn($q) => $q->whereDate('received_date', '>=', $this->fromDate))
            ->when($this->toDate, fn($q) => $q->whereDate('received_date', '<=', $this->toDate));
        return view('livewire.finance.sanitary.stock', [
            'items' => SanitaryItem::orderBy('name')->get(),
            'summary' => (clone $query)->selectRaw('SUM(quantity) as received, SUM(remaining_quantity) as available, SUM(remaining_quantity * unit_cost) as value')->first(),
            'records' => $query->orderByDesc('received_date')->orderByDesc('id')->paginate(20),
        ]);
    }
}
