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
    public function index($sectionClassId) {
        
        return view('finance.payments.index',['sectionClass'=>SectionClass::find($sectionClassId)]);
    }

    public function receipt($paymentId) {
        
        return view('finance.payments.receipt',['payment'=>Payment::find($paymentId)]);
    }

    public function classes($sectionId) {
        
        return view('finance.payments.classes',['section'=>Section::find($sectionId)]);
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

        return redirect()->route('finance.payments.index',[$sectionClassStudent->sectionClass->section->id])->withSuccess('Payment Registered');
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

        $payment->update([
            'term_id'=>$request->term,
            'amount'=>$request->amount,
            'mode'=>$request->mode,
            'section_class_fee_id'=>$request->class_fee,
            'date'=>$request->date,
        ]);

        return redirect()->route('finance.payments.index',[$payment->sectionClassStudent->sectionClass->section->id])->withSuccess('Payment Updated');
    }

    public function delete($paymentId) {
        
        $payment = Payment::find($paymentId);
        
        $payment->delete();

        return redirect()->route('finance.payments.index',[$payment->sectionClassStudent->sectionClass->section->id])->withSuccess('Payment Deleted');
    }


}
