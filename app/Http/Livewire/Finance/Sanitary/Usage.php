<?php

namespace App\Http\Livewire\Finance\Sanitary;

use App\Models\SanitaryItem;
use App\Models\SanitaryStock;
use App\Models\SanitaryUsage;
use App\Services\Finance\SanitaryMaterials;

class Usage extends Screen
{
    public $usageId, $sanitary_item_id = '', $sanitary_stock_id = '', $quantity = 1, $usage_date;
    public $used_by = '', $location = '', $notes = '';

    public function mount() { $this->usage_date = now()->toDateString(); }
    public function updatedSanitaryItemId() { $this->sanitary_stock_id = ''; }

    public function saveUsage()
    {
        app(SanitaryMaterials::class)->saveUsage($this->only([
            'sanitary_item_id', 'sanitary_stock_id', 'quantity', 'usage_date', 'used_by', 'location', 'notes',
        ]), $this->usageId);
        $this->resetForm();
        session()->flash('success', 'Daily usage saved and stock balance updated.');
    }

    public function editUsage($id)
    {
        $this->authorizeManagement();
        $usage = SanitaryUsage::with('stock')->findOrFail($id);
        $this->usageId = $usage->id;
        $this->sanitary_item_id = $usage->stock->sanitary_item_id;
        foreach (['sanitary_stock_id', 'quantity', 'used_by', 'location', 'notes'] as $field) $this->$field = $usage->$field;
        $this->usage_date = $usage->usage_date->toDateString();
        $this->resetValidation();
    }

    public function deleteUsage($id)
    {
        app(SanitaryMaterials::class)->deleteUsage($id);
        if ((int) $this->usageId === (int) $id) $this->resetForm();
        $this->resetPage();
        session()->flash('success', 'Usage deleted and stock restored.');
    }

    public function resetForm()
    {
        $this->reset(['usageId', 'sanitary_item_id', 'sanitary_stock_id', 'quantity', 'usage_date', 'used_by', 'location', 'notes']);
        $this->usage_date = now()->toDateString();
        $this->resetValidation();
    }

    public function render()
    {
        $query = SanitaryUsage::with(['stock.item', 'user'])
            ->when($this->filterItem, fn($q) => $q->whereHas('stock', fn($q) => $q->where('sanitary_item_id', $this->filterItem)))
            ->when($this->search, fn($q) => $q->where(fn($q) => $q->where('used_by', 'like', '%'.$this->search.'%')
                ->orWhere('location', 'like', '%'.$this->search.'%')->orWhereHas('stock.item', fn($q) => $q->where('name', 'like', '%'.$this->search.'%'))))
            ->when($this->fromDate, fn($q) => $q->whereDate('usage_date', '>=', $this->fromDate))
            ->when($this->toDate, fn($q) => $q->whereDate('usage_date', '<=', $this->toDate));
        return view('livewire.finance.sanitary.usage', [
            'items' => SanitaryItem::orderBy('name')->get(),
            'batches' => SanitaryStock::where('sanitary_item_id', $this->sanitary_item_id)
                ->where(fn($q) => $q->where('remaining_quantity', '>', 0)->when($this->usageId, fn($q) => $q->orWhere('id', $this->sanitary_stock_id)))
                ->orderBy('received_date')->orderBy('id')->get(),
            'summary' => (clone $query)->selectRaw('SUM(quantity) as quantity, SUM(quantity * unit_cost) as value')->first(),
            'records' => $query->orderByDesc('usage_date')->orderByDesc('id')->paginate(20),
        ]);
    }
}
