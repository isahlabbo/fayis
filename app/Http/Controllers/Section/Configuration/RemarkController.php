<?php

namespace App\Http\Controllers\Section\Configuration;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RemarkScale;

class RemarkController extends Controller
{
    public function update(Request $request, $remarkScaleId)
    {
        $request->validate([
            'scale'=>"required",
            'remark'=>"required",
            'percent'=>"required",
            'grade'=>"required"
            ]);
        $remark = RemarkScale::find($remarkScaleId);
        $remark->update([
            'grade'=>$request->grade,
            'scale'=>$request->scale,
            'percent'=>$request->percent,
            'remark'=>$request->remark,
            ]);
        return redirect()->route('dashboard.section.configuration.reportcard.index')->withSuccess('Remark Updated');
    }

    public function delete($remarkScaleId)
    {
        
        $remark = RemarkScale::find($remarkScaleId);
        $remark->delete();

        return redirect()->route('dashboard.section.configuration.reportcard.index')->withSuccess('Remark Deleted');
    }

    public function register(Request $request)
    {
        $request->validate([
            'scale'=>"required",
            'remark'=>"required",
            'percent'=>"required",
            'grade'=>"required"
            ]);

        $remark = RemarkScale::firstOrCreate([
            'grade'=>$request->grade,
            'scale'=>$request->scale,
            'percent'=>$request->percent,
            'remark'=>$request->remark,
            ]);
       
        return redirect()->route('dashboard.section.configuration.reportcard.index')->withSuccess('Remark Registered');
    }
}
