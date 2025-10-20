<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SubjectTeacherTermlyUpload;
use App\Models\StudentResult;

class FirstCAController extends Controller
{
    public function index($uploadId) {
        return view('teacher.subject.firstca.index',['upload'=>SubjectTeacherTermlyUpload::find($uploadId)]);
    }

    public function store(Request $request) {

        foreach($request->scores as $studentResultId => $firstCAScore){
            $result = StudentResult::find($studentResultId);
            $result->update(['first_ca'=>$firstCAScore]);
            $result->first_ca = $firstCAScore;
            $result->save();

            $result->updateTotalAndComputeGrade();
        }


        return redirect()->route('teacher.subject.firstca.index',[$result->subjectTeacherTermlyUpload->sectionClassSubjectTeacher->id])->withSuccess('First CA Score Uploaded');
    }
}
