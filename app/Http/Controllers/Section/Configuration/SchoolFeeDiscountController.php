<?php

namespace App\Http\Controllers\Section\Configuration;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\StaffChildrenDiscount;
use App\Models\NeighboringStateDiscount;
use App\Models\SectionClass;
use App\Models\Term;

class SchoolFeeDiscountController extends Controller
{
    public function index()
    {
        return view('section.configuration.fee.index');
    }

    function testFee(Request $request) {
        $sectionClass = SectionClass::find($request->class);
        $stateSchoolFee = $sectionClass->stateSchoolFee(
            Term::find($request->term),
            $request->state,
            $request->student_type, 
            $request->gender);
        dd($stateSchoolFee);
    }

    public function neighboringStateDiscount(Request $request)
    {
        foreach($request->all() as $key=>$value){
            if($stateDiscount = NeighboringStateDiscount::find($key)){
                $stateDiscount->update(['amount'=>$value]);
            }
        }

        return redirect()->route('dashboard.section.configuration.discount.index')->withSuccess('Discount Updated');
    }

    public function staffChildDiscount (Request $request)
    {
        foreach($request->all() as $key=>$value){
            if($staffChildrenDiscount = StaffChildrenDiscount::find($key)){
                $staffChildrenDiscount->update(['amount'=>$value]);
            }
        }

        return redirect()->route('dashboard.section.configuration.discount.index')->withSuccess('Discount Updated');
    }
}
