<?php

namespace App\Http\Controllers\Section\Configuration;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GradeScale;

class GradeController extends Controller
{
    public function update(Request $request, $gradeScaleId)
    {
        $request->validate(['to'=>"required",'from'=>"required",'grade'=>"required"]);
        $grade = GradeScale::find($gradeScaleId);
        $grade->update([
            'grade'=>$request->grade,
            'from'=>$request->from,
            'to'=>$request->to,
        ]);
        return redirect()->route('dashboard.section.configuration.reportcard.index')->withSuccess('Grade Updated');
    }

    public function delete($gradeScaleId)
    {
        
        $grade = GradeScale::find($gradeScaleId);
        $grade->delete();
        return redirect()->route('dashboard.section.configuration.reportcard.index')->withSuccess('Grade Deleted');
    }

    public function register(Request $request)
    {
        $request->validate(['to'=>"required",'from'=>"required",'grade'=>"required"]);
        
        $grade = GradeScale::firstOrCreate([
            'grade'=>$request->grade,
            'from'=>$request->from,
            'to'=>$request->to
        ]);
       
        return redirect()->route('dashboard.section.configuration.reportcard.index')->withSuccess('Grade Created');
    }
}
