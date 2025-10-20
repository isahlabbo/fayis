<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SubjectTeacherTermlyUpload;
use App\Models\StudentResult;

class SecondCAController extends Controller
{
    public function index($uploadId) {
        return view('teacher.subject.secondca.index',['upload'=>SubjectTeacherTermlyUpload::find($uploadId)]);
    }

    public function store(Request $request) {

        foreach($request->scores as $studentResultId => $secondCAScore){
            $result = StudentResult::find($studentResultId);
            $result->second_ca = $secondCAScore;
            $result->save();

            $result->updateTotalAndComputeGrade();
        }


        return redirect()->route('teacher.subject.secondca.index',[$result->subjectTeacherTermlyUpload->sectionClassSubjectTeacher->id])->withSuccess('Second CA Score Uploaded');
    }
}
