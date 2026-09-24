<?php

namespace App\Services\Finance;

use App\Models\SectionClassStudent;

class FeeBalances
{
    public const RELATIONS = [
        'student', 'sectionClass.sectionClassFees.fee',
        'sectionClass.sectionClassFees.sectionClassFeeItems.term',
        'academicSession.academicSessionTerms', 'payments.sectionClassFee',
    ];

    public function forEnrolment(SectionClassStudent $enrolment)
    {
        $enrolment->loadMissing(self::RELATIONS);
        $termIds = optional($enrolment->academicSession)->academicSessionTerms;
        $termIds = $termIds ? $termIds->pluck('term_id')->all() : [];
        $payments = $enrolment->payments->where('academic_session_id', $enrolment->academic_session_id);

        return $enrolment->sectionClass->sectionClassFees->groupBy('fee_id')->flatMap(function ($fees) use ($enrolment, $termIds, $payments) {
            $fee = $fees->first();
            $items = $fees->flatMap->sectionClassFeeItems->filter(function ($item) use ($enrolment, $termIds) {
                return in_array($item->term_id, $termIds)
                    && ($item->gender_id === null || (string) $item->gender_id === (string) $enrolment->student->gender_id);
            });

            return $items->groupBy('term_id')->map(function ($items, $termId) use ($fee, $payments) {
                $due = round((float) $items->sum('amount'), 2);
                $paid = round((float) $payments->filter(function ($payment) use ($fee, $termId) {
                    return (int) $payment->term_id === (int) $termId
                        && (int) optional($payment->sectionClassFee)->fee_id === (int) $fee->fee_id;
                })->sum('amount'), 2);

                return (object) [
                    'fee_id' => $fee->fee_id, 'fee' => $fee->fee,
                    'term_id' => $termId, 'term' => $items->first()->term,
                    'due' => $due, 'paid' => $paid, 'balance' => max(0, round($due - $paid, 2)),
                    'status' => $paid <= 0 ? 'Unpaid' : ($paid < $due ? 'Partial' : 'Paid'),
                ];
            })->values();
        })->values();
    }
}
