<?php

namespace App\Http\Controllers\Examination;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Section;
use App\Models\SectionClass;
use App\Models\StudentResult;

class ResultController extends Controller
{
    public function update(Request $request, $studentResultId) {
        $request->validate([
            'first_ca'=>'required|numeric|min:0|max:20',
            'second_ca'=>'required|numeric|min:0|max:20',
            'exam'=>'required|numeric|min:0|max:60',
        ]);

        $result = StudentResult::find($studentResultId);
        $result->first_ca = $request->first_ca;
        $result->second_ca = $request->second_ca;
        $result->exam = $request->exam;
        $result->save();
        $result->updateTotalAndComputeGrade();

        return redirect()->route('exam.upload.details',[$result->subjectTeacherTermlyUpload->id])->withSuccess('Result Updated Successfully');
    }

    public function publish($sectionClassId) {
        $sectionClass = SectionClass::find($sectionClassId);
        foreach($sectionClass->sectionClassStudents->where('status', 'Active') as $studentInClass){
            foreach($studentInClass->sectionClassStudentTerms->where('academic_session_id', $this->currentSessionTerm()->id) as $studentTerm){
                $publish = $studentTerm->sectionClassStudentTermPublishResult()->firstOrCreate();
                $publish->updatePublishRecord();
            }
        }

        return redirect()->route('exam.upload.summary',[$sectionClassId])->withSuccess('Results Published Successfully');
    }

    public function accessCode($sectionId) {
        $section = Section::find($sectionId);
        return view('exam.upload.result.accessCode', ['section'=>$section]);
    }
}
