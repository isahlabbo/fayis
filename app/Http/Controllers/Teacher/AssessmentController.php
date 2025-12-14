<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SectionClassTeacher;
use App\Models\SectionClassStudentTerm;

class AssessmentController extends Controller
{
    public function index($classTeacherId){
        return view('teacher.class.assessment.index',['classTeacher'=>SectionClassTeacher::find($classTeacherId)]);
    }

    public function edit($studentTermId) {
        return view('teacher.class.assessment.edit',['studentTerm'=>SectionClassStudentTerm::find($studentTermId)]);
    }

    public function update(Request $request, $studentTermId){
        
        $request->validate([
            'teacher_comment'=>'required',
            'head_of_school_comment'=>'required',
        ]);

        $traits = $request->traits;
        $psychomotors = $request->psychomotors;

        $studentTerm = SectionClassStudentTerm::find($studentTermId);
        $assessment = $studentTerm->sectionClassStudentTermAccessment()->firstOrCreate([
            'days_school_open'=>1,
            'days_present'=>1,
            'days_absent'=>1,
            'teacher_comment_id'=>$request->teacher_comment,
            'head_teacher_comment_id'=>$request->head_of_school_comment,
        
        ]);
        

        foreach($traits as $traitId => $traitRate){
            if($traitRate){
                $newTrait = $assessment->sectionClassStudentTermAccessmentAffectiveTraits()->firstOrCreate([]);
                $newTrait->update(['value'=>$traitRate]);
            }
        }
        foreach($psychomotors as $psychomotorId => $psychomotorRate){
            if($psychomotorRate){
                $newpsychomotor = $assessment->sectionClassStudentTermAccessmentPsychomotors()->firstOrCreate([]);
                $newpsychomotor->update(['value'=>$psychomotorRate]);
            }
        }

        return redirect()->route('teacher.class.assessment.index',[$studentTerm->sectionClassStudent->sectionClass->id]);
    }
}
