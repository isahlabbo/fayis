<?php

namespace App\Http\Livewire\Finance\Sanitary;

use App\Models\SanitaryItem;
use App\Models\FinanceActivityLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class Items extends Screen
{
    public $itemId, $name = '', $sku = '', $unit = '', $reorder_level = 0, $notes = '';

    public function saveItem()
    {
        $this->authorizeManagement();
        $this->name = trim($this->name);
        $this->sku = trim($this->sku ?? '') ?: null;
        $this->unit = trim($this->unit);
        $data = $this->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('sanitary_items')->ignore($this->itemId)],
            'sku' => ['nullable', 'string', 'max:100', Rule::unique('sanitary_items')->ignore($this->itemId)],
            'unit' => 'required|string|max:50', 'reorder_level' => 'required|integer|min:0|max:100000000',
            'notes' => 'nullable|string|max:1000',
        ]);
        DB::transaction(function () use ($data) {
            $item = $this->itemId ? SanitaryItem::lockForUpdate()->findOrFail($this->itemId) : new SanitaryItem();
            $before = $item->exists ? $item->toArray() : null;
            $item->fill($data)->save();
            FinanceActivityLog::record('sanitary_item_saved', $item, 'Sanitary item saved: '.$item->name,
                0, ['before' => $before, 'after' => $item->toArray()]);
        });
        $this->resetForm();
        session()->flash('success', 'Sanitary item saved.');
    }

    public function editItem($id)
    {
        $this->authorizeManagement();
        $item = SanitaryItem::findOrFail($id);
        $this->itemId = $item->id;
        foreach (['name', 'sku', 'unit', 'reorder_level', 'notes'] as $field) $this->$field = $item->$field;
        $this->resetValidation();
    }

    public function deleteItem($id)
    {
        $this->authorizeManagement();
        DB::transaction(function () use ($id) {
            $item = SanitaryItem::lockForUpdate()->findOrFail($id);
            if ($item->stocks()->exists()) {
                throw ValidationException::withMessages(['item_delete' => 'This item has stock history and cannot be deleted.']);
            }
            FinanceActivityLog::record('sanitary_item_deleted', $item, 'Sanitary item deleted: '.$item->name,
                0, ['before' => $item->toArray()]);
            $item->delete();
        });
        if ((int) $this->itemId === (int) $id) $this->resetForm();
        $this->resetPage();
        session()->flash('success', 'Sanitary item deleted.');
    }

    public function resetForm()
    {
        $this->reset(['itemId', 'name', 'sku', 'unit', 'reorder_level', 'notes']);
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.finance.sanitary.items', [
            'records' => SanitaryItem::withSum('stocks as available_quantity', 'remaining_quantity')
                ->when($this->search, fn($q) => $q->where(fn($q) => $q->where('name', 'like', '%'.$this->search.'%')
                    ->orWhere('sku', 'like', '%'.$this->search.'%')))
                ->orderBy('name')->paginate(20),
        ]);
    }
}
