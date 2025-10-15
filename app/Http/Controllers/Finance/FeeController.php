<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Fee;
use App\Models\Section;
use App\Models\SectionClass;
use App\Models\SectionClassFee;
use App\Models\SectionClassFeeItem;

class FeeController extends Controller
{
    public function index($sectionClassId) {
        
        return view('finance.fees.index',['sectionClass'=>SectionClass::find($sectionClassId)]);
    }
    public function classes($sectionId) {
        
        return view('finance.fees.classes',['section'=>Section::find($sectionId)]);
    }

    public function addItem(Request $request, $sectionClassFeeId) {
        $sectionClassFeeItem = $request->validate([
            'description' => 'required',
            'amount' => 'required',
        ]);
        $sectionClassFee = SectionClassFee::find($sectionClassFeeId);

        $sectionClassFee->sectionClassFeeItems()->create([
            'description'=>$request->description,
            'amount'=>$request->amount,
            'term_id'=>$request->term,
            'gender_id'=>$request->gender
        ]);

        return redirect()->route('finance.fees.index',[$sectionClassFee->sectionClass->id])->withSuccess('Fees item Registered');
    }

    public function updateItem(Request $request, $sectionClassFeeItemId) {
        $sectionClassFeeItem = $request->validate([
            'description' => 'required',
            'amount' => 'required',
        ]);
        $sectionClassFeeItem = SectionClassFeeItem::find($sectionClassFeeItemId);

        $sectionClassFeeItem->update([
            'description'=>$request->description,
            'amount'=>$request->amount,
            'term_id'=>$request->term,
            'gender_id'=>$request->gender
        ]);

        return redirect()->route('finance.fees.index',[$sectionClassFeeItem->sectionClassFee->sectionClass->id])->withSuccess('Fees item Updated');
    }

    public function deleteItem($sectionClassFeeItemId) {
        
        $sectionClassFeeItem = SectionClassFeeItem::find($sectionClassFeeItemId);
        
        $sectionClassFeeItem->delete();

        return redirect()->route('finance.fees.index',[$sectionClassFeeItem->sectionClassFee->sectionClass->id])->withSuccess('Fees item Deleted');
    }
}
