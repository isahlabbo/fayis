<?php

namespace App\Services\Finance;

use App\Models\FinanceActivityLog;
use App\Models\Payment;
use App\Models\SectionClassStudent;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class RecordFeePayment
{
    public function handle($studentId, $sessionId, $feeId, array $terms, $amount, $mode, $date): Payment
    {
        return DB::transaction(function () use ($studentId, $sessionId, $feeId, $terms, $amount, $mode, $date) {
            // Serialize allocations for this enrolment and recalculate after acquiring the lock.
            $student = SectionClassStudent::where('academic_session_id', $sessionId)->lockForUpdate()->findOrFail($studentId);
            $terms = collect($terms)->map(fn ($id) => (int) $id)->unique()->sort()->values();
            $balances = app(FeeBalances::class)->forEnrolment($student)->where('fee_id', $feeId)->whereIn('term_id', $terms->all());
            $remaining = (int) round((float) $amount * 100);
            $available = (int) round($balances->sum('balance') * 100);
            if ($remaining <= 0 || $remaining > $available) {
                throw ValidationException::withMessages(['amount' => 'Enter an amount between 0.01 and the outstanding balance of '.number_format($available / 100, 2).'.']);
            }
            $classFee = $student->sectionClass->sectionClassFees->firstWhere('fee_id', $feeId);
            $group = (string) Str::uuid();
            $first = null;
            foreach ($terms as $termId) {
                $balance = $balances->firstWhere('term_id', $termId);
                $allocation = min($remaining, (int) round(($balance->balance ?? 0) * 100));
                if ($allocation <= 0) continue;
                $line = Payment::create([
                    'section_class_student_id' => $student->id, 'section_class_fee_id' => $classFee->id,
                    'academic_session_id' => $sessionId, 'term_id' => $termId, 'user_id' => auth()->id(),
                    'amount' => $allocation / 100, 'mode' => $mode, 'date' => $date, 'receipt_group' => $group,
                ]);
                $first = $first ?: $line;
                $remaining -= $allocation;
            }
            FinanceActivityLog::record('payment', $first, 'Payment recorded for '.$student->student->name,
                round((float) $amount, 2), ['fee_id' => $feeId, 'terms' => $terms->all(), 'receipt_group' => $group]);
            return $first;
        });
    }
}
