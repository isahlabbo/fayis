<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\Section;
use App\Models\Payment;
use App\Models\SectionClass;
use App\Models\SectionClassStudent;

class PaymentController extends Controller
{
    public function index(Request $request, $sectionClassId) {
        $sectionClass = SectionClass::find($sectionClassId);
        $feeType = $request->query('type', 'all');
        $sectionClassFees = $sectionClass->sectionClassFees;

        if ($feeType === 'school') {
            $sectionClassFees = $sectionClassFees->filter(function ($fee) {
                return $fee->fee->name === config('fees.payments.school');
            });
        } elseif ($feeType === 'pta') {
            $sectionClassFees = $sectionClassFees->filter(function ($fee) {
                return $fee->fee->name === config('fees.payments.pta');
            });
        } elseif ($feeType === 'sisco') {
            $sectionClassFees = $sectionClassFees->filter(function ($fee) {
                return $fee->fee->name === config('fees.payments.sisco');
            });
        }

        return view('finance.payments.index', [
            'sectionClass' => $sectionClass,
            'sectionClassFees' => $sectionClassFees,
            'feeType' => $feeType,
        ]);
    }

    public function receipt($paymentId) {
        $payment = Payment::findOrFail($paymentId);
        $payments = $payment->receipt_group
            ? Payment::with(['term','sectionClassFee.fee'])->where('receipt_group',$payment->receipt_group)->orderBy('term_id')->get()
            : collect([$payment->load(['term','sectionClassFee.fee'])]);
        return view('finance.payments.receipt', compact('payment','payments'));
    }

    public function receiptPdf($paymentId)
    {
        $payment=Payment::findOrFail($paymentId);
        $payments=$payment->receipt_group?Payment::with(['term','sectionClassFee.fee'])->where('receipt_group',$payment->receipt_group)->orderBy('term_id')->get():collect([$payment->load(['term','sectionClassFee.fee'])]);
        return response()->view('finance.payments.receipt-pdf',compact('payment','payments'),200, [
            'Content-Type'=>'text/html; charset=UTF-8',
            'Content-Disposition'=>'inline; filename="payment-receipt-'.$payment->id.'.html"',
        ]);
    }

    public function classes($sectionId, $feeType = null) {
        $section = Section::find($sectionId);
        $feeType = in_array($feeType, ['school', 'pta', 'sisco']) ? $feeType : 'all';

        return view('finance.payments.classes', [
            'section' => $section,
            'feeType' => $feeType,
        ]);
    }

    public function add(Request $request, $sectionClasspaymentId) {
        $request->validate([
            'student' => 'required',
            'amount' => 'required',
            'mode' => 'required',
            'term' => 'required',
            'class_fee' => 'required',
            'date' => 'required',
        ]);
        $type = $request->input('type');
        $sectionClassStudent = SectionClassStudent::find($request->student);

        $sectionClassStudent->payments()->create([
            'term_id'=>$request->term,
            'academic_session_id'=>$sectionClassStudent->currentSession()->id,
            'user_id'=>Auth::user()->id,
            'amount'=>$request->amount,
            'mode'=>$request->mode,
            'section_class_fee_id'=>$request->class_fee,
            'date'=>$request->date,
        ]);

        $redirectParams = [$sectionClassStudent->sectionClass->id];
        if (in_array($type, ['school', 'pta'])) {
            $redirectParams['type'] = $type;
        }

        return redirect()->route('finance.payments.index', $redirectParams)->withSuccess('Payment Registered');
    }

    public function update(Request $request, $paymentId) {
        $request->validate([
            'student' => 'required',
            'amount' => 'required',
            'mode' => 'required',
            'term' => 'required',
            'class_fee' => 'required',
            'date' => 'required',
        ]);
        $payment = Payment::find($paymentId);
        $type = $request->input('type');

        $payment->update([
            'section_class_student_id'=>$request->student,
            'term_id'=>$request->term,
            'amount'=>$request->amount,
            'mode'=>$request->mode,
            'section_class_fee_id'=>$request->class_fee,
            'date'=>$request->date,
        ]);

        $redirectParams = [$payment->sectionClassStudent->sectionClass->id];
        if (in_array($type, ['school', 'pta'])) {
            $redirectParams['type'] = $type;
        }

        return redirect()->route('finance.payments.index', $redirectParams)->withSuccess('Payment Updated');
    }

    public function delete($paymentId) {
        
        $payment = Payment::find($paymentId);
        
        $payment->delete();

        return redirect()->route('finance.payments.index',[$payment->sectionClassStudent->sectionClass->section->id])->withSuccess('Payment Deleted');
    }


}
