<?php

namespace App\Http\Controllers\Section;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Section;
use App\Models\SectionClass;

class SectionResultController extends Controller
{
    public function index($sectionId)
    {
        return view('section.result.index',['section'=>Section::find($sectionId)]);
    }

    public function classAwaitingResult($sectionClassId)
    {
        return view('section.result.awaiting',['sectionClass'=>SectionClass::find($sectionClassId)]);
    }

    public function publishResult($sectionClassId)
    {
        $sectionClass = SectionClass::find($sectionClassId);
        foreach($sectionClass->sectionClassStudents->where('status','Active') as $sectionClassStudent){
            foreach($sectionClassStudent->sectionClassStudentTerms as $sectionClassStudentTerm){
                $sectionClassStudentTerm->publishThisTermResult();
            }
        }
        return back()->withSuccess('Result published successfully');
    }
}
