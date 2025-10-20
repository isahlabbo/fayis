<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SubjectTeacherTermlyUpload;
use App\Models\StudentResult;

class ExamController extends Controller
{
    public function index($uploadId) {
        return view('teacher.subject.exam.index',['upload'=>SubjectTeacherTermlyUpload::find($uploadId)]);
    }

    public function store(Request $request) {

        foreach($request->scores as $studentResultId => $examScore){
            $result = StudentResult::find($studentResultId);
            $result->exam = $examScore;
            $result->save();

            $result->updateTotalAndComputeGrade();
        }

        return redirect()->route('teacher.subject.exam.index',[$result->subjectTeacherTermlyUpload->sectionClassSubjectTeacher->id])->withSuccess('Exam Score Uploaded');
    }
}
