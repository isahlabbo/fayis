<?php

namespace App\Http\Controllers\Section\Configuration;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Section;
use App\Models\GradeScale;
use App\Models\RemarkScale;
use App\Models\Psychomotor;
use App\Models\AffectiveTrait;
use App\Models\AdmissionLetter;

class ReportCardConfigurationController extends Controller
{
    public function index()
    {
        
        return view('section.configuration.reportcard',[
            'sections'=>Section::all(),
            'gradeScales'=>GradeScale::all(),
            'remarkScales'=>RemarkScale::all(),
            'psychomotors'=>Psychomotor::all(),
            'affectiveTraits'=>AffectiveTrait::all()
            ]);
    }

    public function updateLetter(Request $request)
    {
        $letter = AdmissionLetter::find(1);
        foreach($request->all() as $field => $data){
            if($field != "_token"){
                $letter->update([$field=>$data]);
            }
        }
        return redirect()->route('dashboard.section.configuration.reportcard.index');
    }

}
