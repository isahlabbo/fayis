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
            'days_school_open'=>'required',
            'days_present'=>'required',
            'days_absent'=>'required',
        ]);

        $traits = $request->traits;
        $psychomotors = $request->psychomotors;

        $studentTerm = SectionClassStudentTerm::find($studentTermId);
        if($request->days_school_open == ($request->days_present+$request->days_absent)){
            $assessment = $studentTerm->sectionClassStudentTermAccessment()->firstOrCreate([
                'days_school_open'=>$request->days_school_open,
                'days_present'=>$request->days_present,
                'days_absent'=>$request->days_absent,
                'teacher_comment_id'=>$request->teacher_comment,
                'head_teacher_comment_id'=>$request->head_of_school_comment,
            ]);
           
            foreach($traits as $traitId => $traitRate){
                if($traitRate){
                    $newTrait = $assessment->sectionClassStudentTermAccessmentAffectiveTraits()->firstOrCreate(['affective_trait_id'=>$traitId]);
                    $newTrait->value= $traitRate;
                    $newTrait->save();
                }
            }
            foreach($psychomotors as $psychomotorId => $psychomotorRate){
                if($psychomotorRate){
                    $newpsychomotor = $assessment->sectionClassStudentTermAccessmentPsychomotors()->firstOrCreate(['psychomotor_id'=>$psychomotorId]);
                    $newpsychomotor->value = $psychomotorRate;
                    $newpsychomotor->save();
                }
            }
            return redirect()->route('teacher.class.assessment.index',[$studentTerm->sectionClassStudent->sectionClass->activeClassTeacher()->id]);
        }
        
        return redirect()->route('teacher.class.assessment.index',[$studentTerm->sectionClassStudent->sectionClass->activeClassTeacher()->id])->withWarning('Invalid Attendance Record: Days prsent + Days Absent must be equal to Days School Open');
    }
}
