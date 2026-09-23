<?php

namespace App\Services\Finance;

use App\Models\FinanceActivityLog;
use App\Models\SanitaryItem;
use App\Models\SanitaryStock;
use App\Models\SanitaryUsage;
use App\Support\SanitaryAccess;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class SanitaryMaterials
{
    private function authorize(): void
    {
        abort_unless(SanitaryAccess::canManage(Auth::user()), 403);
    }

    public function saveStock(array $input, $id = null): SanitaryStock
    {
        $this->authorize();
        $data = Validator::make($input, [
            'sanitary_item_id' => 'required|integer|exists:sanitary_items,id',
            'quantity' => 'required|integer|min:1|max:1000000',
            'unit_cost' => 'required|numeric|min:0|max:99999999.99',
            'received_date' => 'required|date_format:Y-m-d|before_or_equal:today',
            'supplier' => 'nullable|string|max:255', 'reference' => 'nullable|string|max:100',
            'notes' => 'nullable|string|max:1000',
        ])->validate();

        return DB::transaction(function () use ($data, $id) {
            SanitaryItem::lockForUpdate()->findOrFail($data['sanitary_item_id']);
            $stock = $id ? SanitaryStock::lockForUpdate()->findOrFail($id) : new SanitaryStock();
            $before = $stock->exists ? $stock->toArray() : null;
            $used = $stock->exists ? $stock->quantity - $stock->remaining_quantity : 0;
            if ($stock->exists && (int) $stock->sanitary_item_id !== (int) $data['sanitary_item_id']) {
                throw ValidationException::withMessages(['sanitary_item_id' => 'The item of an existing stock batch cannot be changed.']);
            }
            if ($data['quantity'] < $used) {
                throw ValidationException::withMessages(['quantity' => 'Quantity cannot be less than the '.$used.' units already used.']);
            }
            if ($stock->exists && $stock->usages()->whereDate('usage_date', '<', $data['received_date'])->exists()) {
                throw ValidationException::withMessages(['received_date' => 'Receipt date cannot be later than usage recorded for this batch.']);
            }
            $stock->fill($data);
            $stock->remaining_quantity = $data['quantity'] - $used;
            if (!$stock->exists) $stock->user_id = Auth::id();
            $stock->save();
            // Correcting a batch cost also corrects its recorded consumption value.
            $stock->usages()->update(['unit_cost' => $stock->unit_cost]);
            FinanceActivityLog::record($id ? 'sanitary_stock_updated' : 'sanitary_stock_received',
                $stock, 'Sanitary stock: '.$stock->item->name, $stock->quantity * $stock->unit_cost,
                ['before' => $before, 'after' => $stock->toArray()]);
            return $stock;
        });
    }

    public function deleteStock($id): void
    {
        $this->authorize();
        DB::transaction(function () use ($id) {
            $stock = SanitaryStock::lockForUpdate()->findOrFail($id);
            if ($stock->usages()->exists() || $stock->quantity != $stock->remaining_quantity) {
                throw ValidationException::withMessages(['stock_delete' => 'This batch has recorded usage and cannot be deleted.']);
            }
            FinanceActivityLog::record('sanitary_stock_deleted', $stock, 'Unused sanitary stock deleted',
                -$stock->quantity * $stock->unit_cost, ['before' => $stock->toArray()]);
            $stock->delete();
        });
    }

    public function saveUsage(array $input, $id = null): SanitaryUsage
    {
        $this->authorize();
        $data = Validator::make($input, [
            'sanitary_item_id' => 'required|integer|exists:sanitary_items,id',
            'sanitary_stock_id' => 'required|integer|exists:sanitary_stocks,id',
            'quantity' => 'required|integer|min:1|max:1000000',
            'usage_date' => 'required|date_format:Y-m-d|before_or_equal:today',
            'used_by' => 'required|string|max:255', 'location' => 'required|string|max:255',
            'notes' => 'nullable|string|max:1000',
        ])->validate();

        return DB::transaction(function () use ($data, $id) {
            // Always lock the batch before its usage rows, including corrections.
            $stock = SanitaryStock::lockForUpdate()->findOrFail($data['sanitary_stock_id']);
            $usage = $id ? SanitaryUsage::lockForUpdate()->findOrFail($id) : new SanitaryUsage();
            if ((int) $stock->sanitary_item_id !== (int) $data['sanitary_item_id']) {
                throw ValidationException::withMessages(['sanitary_stock_id' => 'Select a batch belonging to this item.']);
            }
            if ($usage->exists && (int) $usage->sanitary_stock_id !== (int) $stock->id) {
                throw ValidationException::withMessages(['sanitary_stock_id' => 'The batch cannot be changed. Delete this usage and record it against the correct batch.']);
            }
            if ($data['usage_date'] < $stock->received_date->toDateString()) {
                throw ValidationException::withMessages(['usage_date' => 'Usage date cannot be before the stock receipt date.']);
            }
            $available = $stock->remaining_quantity + ($usage->exists ? $usage->quantity : 0);
            if ($data['quantity'] > $available) {
                throw ValidationException::withMessages(['quantity' => 'Only '.$available.' units are available in this batch.']);
            }
            $before = $usage->exists ? $usage->toArray() : null;
            unset($data['sanitary_item_id']);
            $usage->fill($data);
            $usage->unit_cost = $stock->unit_cost;
            if (!$usage->exists) $usage->user_id = Auth::id();
            $usage->save();
            $stock->update(['remaining_quantity' => $available - $usage->quantity]);
            FinanceActivityLog::record($id ? 'sanitary_usage_updated' : 'sanitary_usage_recorded',
                $usage, 'Sanitary material used: '.$stock->item->name, $usage->quantity * $usage->unit_cost,
                ['before' => $before, 'after' => $usage->toArray()]);
            return $usage;
        });
    }

    public function deleteUsage($id): void
    {
        $this->authorize();
        DB::transaction(function () use ($id) {
            $batchId = SanitaryUsage::findOrFail($id)->sanitary_stock_id;
            $stock = SanitaryStock::lockForUpdate()->findOrFail($batchId);
            $usage = SanitaryUsage::lockForUpdate()->findOrFail($id);
            FinanceActivityLog::record('sanitary_usage_deleted', $usage, 'Sanitary usage deleted; stock restored',
                -$usage->quantity * $usage->unit_cost, ['before' => $usage->toArray()]);
            $stock->increment('remaining_quantity', $usage->quantity);
            $usage->delete();
        });
    }
}
