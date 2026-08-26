<?php

namespace App\Services\Finance;

use App\Models\AdvancePayment;
use App\Models\FinanceActivityLog;
use App\Models\Payment;
use App\Models\SectionClassFeeItem;
use App\Models\SectionClassStudent;
use Illuminate\Support\Facades\DB;

class ApplyAdvancePayments
{
    public function handle(SectionClassStudent $enrolment): void
    {
        DB::transaction(function () use ($enrolment) {
            $advances = AdvancePayment::where('student_id', $enrolment->student_id)
                ->where('academic_session_id', $enrolment->academic_session_id)
                ->where('section_class_id', $enrolment->section_class_id)
                ->whereIn('status', ['Pending', 'Partially Applied'])
                ->lockForUpdate()->get();

            foreach ($advances as $advance) {
                $classFee = $enrolment->sectionClass->sectionClassFees()->where('fee_id', $advance->fee_id)->first();
                if (!$classFee) continue;
                $due = (float) SectionClassFeeItem::where('section_class_fee_id', $classFee->id)
                    ->where('term_id', $advance->term_id)
                    ->where(fn($q) => $q->whereNull('gender_id')->orWhere('gender_id', optional($enrolment->student)->gender_id))
                    ->sum('amount');
                $paid = (float) Payment::where('section_class_student_id', $enrolment->id)
                    ->where('academic_session_id', $enrolment->academic_session_id)->where('term_id', $advance->term_id)
                    ->where('section_class_fee_id', $classFee->id)->sum('amount');
                $allocation = min($advance->remaining_amount, max(0, $due - $paid));
                if ($allocation <= 0) continue;

                $payment = Payment::create([
                    'section_class_student_id'=>$enrolment->id, 'section_class_fee_id'=>$classFee->id,
                    'academic_session_id'=>$enrolment->academic_session_id, 'term_id'=>$advance->term_id,
                    'user_id'=>$advance->user_id, 'mode'=>$advance->mode, 'amount'=>$allocation,
                    'date'=>$advance->date->toDateString(), 'receipt_group'=>$advance->receipt_group,
                ]);
                $applied = (float) $advance->applied_amount + $allocation;
                $advance->update(['applied_amount'=>$applied, 'applied_payment_id'=>$payment->id,
                    'status'=>$applied >= (float)$advance->amount ? 'Applied' : 'Partially Applied']);
                FinanceActivityLog::record('advance_payment_applied', $advance, 'Advance payment applied to enrolment', $allocation, ['payment_id'=>$payment->id]);
            }
        });
    }
}
