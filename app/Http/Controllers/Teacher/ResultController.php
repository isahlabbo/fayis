<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SectionClass;
use App\Models\StudentResult;
use App\Models\SubjectTeacherTermlyUpload;

class ResultController extends Controller
{
    public function index($sectionClassId) {
        return view('teacher.class.result.index',['sectionClass'=>SectionClass::find($sectionClassId)]);
    }

    public function details($resultId) {
        return view('teacher.class.result.detail',['result'=>SubjectTeacherTermlyUpload::find($resultId)]);
    }

    public function returnForCorrection($resultId) {
        $result = SubjectTeacherTermlyUpload::find($resultId);
        $result->update(['status'=>0]);  
        return redirect()->route('teacher.class.result.index',[$result->sectionClassSubjectTeacher->sectionClassSubject->sectionClass->id])->withSuccess('The result return to subject teacher for correction');
    }

    public function submit($resultId) {
        $result = SubjectTeacherTermlyUpload::find($resultId);
        $result->update(['status'=>2]);  
        return redirect()->route('teacher.class.result.index',[$result->sectionClassSubjectTeacher->sectionClassSubject->sectionClass->id])->withSuccess('The result has been submitted to Exam Officer');
    }

    public function update(Request $request, $studentResultId) {
        $request->validate([
            'first_ca'=>'required|numeric|min:0|max:15',
            'second_ca'=>'required|numeric|min:0|max:15',
            'assignment'=>'required|numeric|min:0|max:10',
            'exam'=>'required|numeric|min:0|max:60',
        ]);

        $result = StudentResult::find($studentResultId);
        $result->first_ca = $request->first_ca;
        $result->second_ca = $request->second_ca;
        $result->assignment = $request->assignment;
        $result->exam = $request->exam;
        $result->save();
        $result->updateTotalAndComputeGrade();

        return redirect()->route('teacher.class.result.details',[$result->subjectTeacherTermlyUpload->id])->withSuccess('Result Updated Successfully');
    }
}
