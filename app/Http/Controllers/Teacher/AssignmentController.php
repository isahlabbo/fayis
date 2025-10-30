<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StudentResult;
use App\Models\SubjectTeacherTermlyUpload;

class AssignmentController extends Controller
{
    public function index($uploadId) {
        return view('teacher.subject.assignment.index',['upload'=>SubjectTeacherTermlyUpload::find($uploadId)]);
    }

    public function store(Request $request) {

        foreach($request->scores as $studentResultId => $assignmentScore){
            $result = StudentResult::find($studentResultId);
            $result->update(['assignment'=>$assignmentScore]);

            $result->updateTotalAndComputeGrade();
        }


        return redirect()->route('teacher.subject.assignment.index',[$result->subjectTeacherTermlyUpload->id])->withSuccess('Assignment Uploaded');
    }
}
