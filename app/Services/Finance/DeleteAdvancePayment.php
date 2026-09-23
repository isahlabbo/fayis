<?php

namespace App\Services\Finance;

use App\Models\AdvancePayment;
use App\Models\FinanceActivityLog;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DeleteAdvancePayment
{
    public function handle($advanceId, $feeId = null): void
    {
        abort_unless(Auth::check() && Auth::user()->hasPermission('manage-payments'), 403);

        DB::transaction(function () use ($advanceId, $feeId) {
            $advance = AdvancePayment::lockForUpdate()->findOrFail($advanceId);
            abort_if($feeId !== null && (int) $advance->fee_id !== (int) $feeId, 403);

            // A partially applied advance can have several payments; the model
            // stores only the latest one, while the activity log records them all.
            $paymentIds = FinanceActivityLog::where('reference_type', AdvancePayment::class)
                ->where('reference_id', $advance->id)
                ->where('activity_type', 'advance_payment_applied')
                ->get()->pluck('metadata.payment_id')
                ->push($advance->applied_payment_id)->filter()->unique()->values();
            $payments = Payment::whereIn('id', $paymentIds)->lockForUpdate()->get();

            FinanceActivityLog::record('advance_payment_deleted', $advance,
                'Mistaken advance payment deleted', -(float) $advance->amount, [
                    'advance_payment' => $advance->toArray(),
                    'payment_ids' => $payments->pluck('id')->all(),
                    'reversed_amount' => (float) $payments->sum('amount'),
                    'receipt_group' => $advance->receipt_group,
                ]);

            $advance->delete();
            Payment::whereIn('id', $payments->pluck('id'))->delete();
        });
    }
}
