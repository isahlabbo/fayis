<?php

namespace App\Http\Controllers\School;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SectionClass;
use App\Models\SectionClassStudent;
use App\Models\TeacherComment;
use App\Models\SectionClassStudentTerm;



class StudentAccessmentController extends Controller
{
    public function index($sectionClassId)
    {
        return view('school.student.accessment.index',['sectionClass'=>SectionClass::find($sectionClassId)]);
    }
    
    public function create($sectionClassStudentId)
    {
        return view('school.student.accessment.create',[
            'sectionClassStudent'=>SectionClassStudent::find($sectionClassStudentId),'comments'=>TeacherComment::all()
            ]);
    }
    
    

    public function register(Request $request)
    {
        $request->validate([
            "punctuality" => "required",
            "attendance" => "required",
            "reliability" => "required",
            "neatness" => "required",
            "politeness" => "required",
            "honesty" => "required",
            "relationship_with_pupils" => "required",
            "self_control" => "required",
            "attentiveness" => "required",
            "perseverance" => "required",
            "handwriting" => "required",
            "games" => "required",
            "sports" => "required",
            "drawing_and_painting" => "required",
            "crafts" => "required",
            "days_school_open" => "required",
            "days_present" => "required",
            "days_absent" => "required",
            "comment" => "required"
        ]);
        $sectionClassStudentTerm = SectionClassStudentTerm::find($request->sectionClassStudentTermId);
        $accessment = $sectionClassStudentTerm->sectionClassStudentTermAccessment;
        if($accessment){
            $accessment->update([
                "punctuality" => $request->punctuality,
                "attendance" => $request->attendance,
                "reliability" => $request->reliability,
                "neatness" => $request->neatness,
                "politeness" => $request->politeness,
                "honesty" => $request->honesty,
                "relationship_with_pupils" => $request->relationship_with_pupils,
                "self_control" => $request->self_control,
                "attentiveness" => $request->attentiveness,
                "perseverance" => $request->perseverance,
                "handwriting" => $request->handwriting,
                "games" => $request->games,
                "sports" => $request->sports,
                "drawing_and_painting" => $request->drawing_and_painting,
                "crafts" => $request->crafts,
                "days_school_open" => $request->days_school_open,
                "days_present" => $request->days_present,
                "days_absent" => $request->days_absent,
                "teacher_comment_id" => $request->comment,
                'head_teacher_comment_id'=> rand(1,10)
            ]);
        }else{
            $sectionClassStudentTerm->sectionClassStudentTermAccessment()->create([
                "punctuality" => $request->punctuality,
                "attendance" => $request->attendance,
                "reliability" => $request->reliability,
                "neatness" => $request->neatness,
                "politeness" => $request->politeness,
                "honesty" => $request->honesty,
                "relationship_with_pupils" => $request->relationship_with_pupils,
                "self_control" => $request->self_control,
                "attentiveness" => $request->attentiveness,
                "perseverance" => $request->perseverance,
                "handwriting" => $request->handwriting,
                "games" => $request->games,
                "sports" => $request->sports,
                "drawing_and_painting" => $request->drawing_and_painting,
                "crafts" => $request->crafts,
                "days_school_open" => $request->days_school_open,
                "days_present" => $request->days_present,
                "days_absent" => $request->days_absent,
                "teacher_comment_id" => $request->comment,
                'head_teacher_comment_id'= rand(1,10),
            ]);
        }

        return redirect()->route('dashboard.student.accessment.index',[$sectionClassStudentTerm->sectionClassStudent->sectionClass->id])
        ->withSuccess('Student Accessed Successfully');
    }
}
