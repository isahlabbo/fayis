<?php

namespace App\Http\Controllers\Section;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Term;
use App\Models\AcademicSession;
use App\Models\SectionClass;
use App\Models\Psychomotor;
use App\Models\AffectiveTrait;
use App\Models\GradeScale;
use App\Models\TeacherComment;
use App\Models\HeadTeacherComment;
use App\Models\RemarkScale;
use App\Models\SectionClassStudentTerm;
use Excel;
use App\Exports\StudentAccessmentExport;
use App\Imports\StudentAccessmentImport;

class ClassResultController extends Controller
{
    public function summary($sectionClassId, $sessionId, $termId)
    {
        return view('section.class.result.summary',['session'=>AcademicSession::find($sessionId),'term'=>Term::find($termId),'sectionClass'=>SectionClass::find($sectionClassId)]);
    }

    public function report($sectionClassId)
    {
        return view('section.class.result.report',['gradeScales'=>GradeScale::all(),'remarkScales'=>RemarkScale::all(),'psychomotors'=>Psychomotor::all(),'affectiveTraits'=>AffectiveTrait::all(),'sectionClass'=>SectionClass::find($sectionClassId)]);
    }

    public function viewAccessment($sectionClassId)
    {
        return view('section.class.result.accessment.view',[
            'sectionClass'=>SectionClass::find($sectionClassId)
            ]);
    }
    public function updateAccessment(Request $request,$sectionClassId, $sectionClassStudentTermId)
    {
        $data = $request->all();
        
        $accessment = SectionClassStudentTerm::find($sectionClassStudentTermId)->sectionClassStudentTermAccessment;
        $sectionClass = SectionClass::find($sectionClassId);
        $accessment->update([
            "days_school_open" => $data['days_school_open'],
            "days_present" => $data['days_present'],
            "days_absent" => $data['days_absent'],
            "teacher_comment_id" => $data['teacher_comment_id'],
            "head_teacher_comment_id" => $data['head_teacher_comment_id']
        ]);

        foreach ($data as $key => $value) {
            $psychomotor = Psychomotor::where(['section_id'=>$sectionClass->section->id,'name'=>$key])->first();
            if($psychomotor){
                $psychoAccessment = $accessment->sectionClassStudentTermAccessmentPsychomotors()->firstOrCreate([
                    'psychomotor_id'=>$psychomotor->id
                    ]);
                $psychoAccessment->update(['value'=>$value]);    
            }else{
                $affectiveTrait = AffectiveTrait::where(['section_id'=>$sectionClass->section->id,'name'=>$key])->first();
                if($affectiveTrait){
                    $traitAccessment = $accessment->sectionClassStudentTermAccessmentAffectiveTraits()->firstOrCreate([
                       'affective_trait_id'=>$affectiveTrait->id]);
                    $traitAccessment->update(['value'=>$value]);    
                }
            }
        } 
        return redirect()->route('dashboard.section.class.result.accessment.edit',[$sectionClass->id,$accessment->sectionClassStudentTerm->id])
        ->withSuccess('Accessment Updated');
    }

    public function editAccessment($sectionClassId,$sectionClassStudentTermId)
    {
        return view('section.class.result.accessment.edit',[
            'teacherComments'=>TeacherComment::all(),
            'headTeacherComments'=>HeadTeacherComment::all(),
            'remarkScales'=>RemarkScale::all(),
            'sectionClass'=>SectionClass::find($sectionClassId),
            'sectionClassStudentTerm'=>SectionClassStudentTerm::find($sectionClassStudentTermId)
            ]);
    }
    
    public function downloadAccessment($sectionClassId)
    {
        $sectionClass = SectionClass::find($sectionClassId);

        return Excel::download(new StudentAccessmentExport($sectionClass), 
        strtolower(str_replace(' ','_',$sectionClass->name)).'_'.str_replace('/','_',$sectionClass->currentSession()->name).
        '_student_accessment.xlsx');
    }

    public function uploadAccessment(request $request, $sectionClassId)
    {
        $request->validate([
            'accessment'=>'required',
        ]);
        $sectionClass = SectionClass::find($sectionClassId);
        Excel::import(new StudentAccessmentImport($sectionClass), request()->file('accessment'));
        
        return redirect()->route('dashboard.section.class.result.summary',[$sectionClass->id,$sectionClass->currentSession()->id,$sectionClass->currentSessionTerm()->term->id])->withSuccess('Student Accessment Uploaded Successfully');
    }

}
