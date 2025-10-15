<?php

namespace App\Http\Controllers\Section;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SectionClassTermlyExam;
use App\Models\SectionClassSubject;

class QuestionSectionController extends Controller
{
    public function index($examId, $subjectId)
    {
        return view('section.class.exam.subject.question.section.index',[
            'exam'=>SectionClassTermlyExam::find($examId),
            'sectionClassSubject'=>SectionClassSubject::find($subjectId)
        ]);
    }

    public function register(Request $request, $sectionClassTermlyExamId, $sectionClassSubjectId)
    {
        $request->validate([
            'name'=>'required',
            'instruction'=>'required',
        ]);
        $sectionClassSubject = SectionClassSubject::find($request->section_class_subject_id);
        $sectionClassSubject->examsubjectQuestionSections()->firstOrCreate([
            'section_class_termly_exam_id'=>$request->section_class_termly_exam_id,
            'name'=>$request->name,
            'instruction'=>$request->instruction,
        ]);
        return redirect()->route('dashboard.section.class.exam.subject.question.section.index',
        [$sectionClassTermlyExamId, $sectionClassSubjectId])->withSuccess('Question Section Registered');
    }
}
