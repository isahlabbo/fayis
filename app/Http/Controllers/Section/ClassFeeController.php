<?php

namespace App\Http\Controllers\Section;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Term;
use App\Models\SectionClass;
use App\Models\SectionClassPayment;

class ClassFeeController extends Controller
{
    public function index($sectionClassId)
    {
        return view('section.payment.class.fee.index',['sectionClass'=>SectionClass::find($sectionClassId),'terms'=>Term::all()]);
    }

    public function register(Request $request, $sectionClassId)
    {
        $request->validate([
            "name" => "required|string",
            "amount" => "required|integer",
            "gender" => "required",
            "student_type" => "required",
            "term" => "required"
        ]);
        $class = SectionClass::find($sectionClassId);

        if($request->assign){
            foreach($class->section->sectionClasses as $sectionClass){
                $sectionClass->sectionClassPayments()->firstOrCreate([
                    "name" => $request->name,
                    "amount" => $request->amount,
                    "gender_id" => $request->gender,
                    "term_id" => $request->term,
                    "student_type_id" => $request->student_type
                ]);
            }
        }else{
            $sectionClass->sectionClassPayments()->firstOrCreate([
                "name" => $request->name,
                "amount" => $request->amount,
                "gender_id" => $request->gender,
                "term_id" => $request->term,
                "student_type_id" => $request->student_type
            ]);
        }
        return redirect()->route('dashboard.section.class.fee.index',[$sectionClassId])->withSuccess('Fee Item Added');    
    }

    public function update(Request $request, $sectionClassId,$feeId)
    {
        $request->validate([
            "name" => "required|string",
            "amount" => "required|integer",
            "gender" => "required",
            "term" => "required",
            "student_type" => "required",
        ]);
        
        $fee = SectionClassPayment::find($feeId);

        $fee->update([
            "name" => $request->name,
            "amount" => $request->amount,
            "gender_id" => $request->gender,
            "term_id" => $request->term,
            "student_type_id" => $request->student_type
        ]);

        return redirect()->route('dashboard.section.class.fee.index',[$sectionClassId])->withSuccess('Fee Item Updated');    
    }

    public function delete($sectionClassId,$feeId)
    {
        $fee = SectionClassPayment::find($feeId);
        $fee->delete();
        return redirect()->route('dashboard.section.class.fee.index',[$sectionClassId])->withSuccess('Fee Item Deleted');
    }
}
