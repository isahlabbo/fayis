<?php

namespace App\Http\Controllers\Section;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Section;
use App\Models\Term;
use App\Models\Student;
use App\Models\StudentResult;
use App\Models\SectionClass;
use App\Models\SectionClassSubject;
use App\Models\SubjectTeacherTermlyUpload;
use App\Events\ResultDeleted;
use App\Events\ResultUpdated;
use App\Models\AcademicSession;

class ResultSearchController extends Controller
{
    public function index()
    {
        return view('section.class.subject.result.search.index',['sections'=>Section::all(),'terms'=>Term::all()]);
    }

    public function checkResult(Request $request)
    {
        $request->validate([
            "session" => "required",
            "term" => "required",
            "section" => "required",
            "class" => "required"
        ]);

        if($request->admission_no){
            $student = Student::where('admission_no',$request->admission_no)->first();
            if($student){
                return redirect()->route('dashboard.section.class.student.result.view',[$student->id]);
            }else{
                return redirect()->route('dashboard.section.class.subject.result.index')->withWarning($request->admission_no.' student record not found');
            }
        }elseif($request->subject){
            $sectionClassSubject = SectionClassSubject::find($request->subject);
            if(count($sectionClassSubject->availableResultUploads($request->session, $request->term))>0){
                return redirect()->route('dashboard.section.class.subject.result.summary',[$sectionClassSubject->id, $request->session, $request->term])->withSuccess(count($sectionClassSubject->availableResultUploads($request->session, $request->term)).' Result Summary Found');
            }else{
                return redirect()->route('dashboard.section.class.subject.result.index')->withWarning('No Result uploaded for '.$sectionClassSubject->sectionClass->name.' '.$sectionClassSubject->subject->name);
            }
        }elseif($request->class){
            $sectionClass = SectionClass::find($request->class);
            if($sectionClass->availableResultUploads($request->session, $request->term)>0){
                return redirect()->route('dashboard.section.class.result.summary',[$sectionClass->id, $request->session,  $request->term])->withSuccess($sectionClass->availableResultUploads($request->session, $request->term).' Result Summary Found');
            }else{
                return redirect()->route('dashboard.section.class.subject.result.index')->withWarning('No Result uploaded for '.$sectionClass->name.' at '.AcademicSession::find($request->session)->name);
            }
        }else{
            return redirect()->route('dashboard.section.class.subject.result.index')->withWarning('PLs give us some thing to search for');
        }
    }

    public function viewResultSummary($sectionClassSubjectId,$sessionId, $termId)
    {
        return view('section.class.subject.result.search.summary',['term'=>Term::find($termId),'session'=>AcademicSession::find($sessionId),'sectionClassSubject'=>SectionClassSubject::find($sectionClassSubjectId)]);
    }

    public function viewDetail($subcetTeacherTermlyUploadId)
    {
        return view('section.class.subject.result.search.detail',['subjectTeacherTermlyUpload'=>SubjectTeacherTermlyUpload::find($subcetTeacherTermlyUploadId)]);
    }

    public function deleteUpload ($subjectTeacherTermlyUploadId)
    {
        $upload = SubjectTeacherTermlyUpload::find($subjectTeacherTermlyUploadId);
        
        foreach ($upload->studentResults as $studentResult) {
            $studentResult->delete();
            event(new ResultDeleted($studentResult));
        }
        $upload->delete();
          
        return redirect()->route('dashboard.section.class.result.summary',[$upload->sectionClassSubjectTeacher->sectionClassSubject->sectionClass->id,
             $upload->academicSessionTerm->academicSession->id,$upload->term_id])->withSuccess(' Result Deleted');
    }

    public function editResult($studentResultId)
    {
        return view('section.class.subject.result.search.edit',['studentResult'=>StudentResult::find($studentResultId)]);
    }
    

    public function updateResult(Request $request)
    {
        $request->validate([
            'first_ca'=>'required',
            'second_ca'=>'required',
            'exam'=>'required'
            ]);
        $studentResult = StudentResult::find($request->studentResultId);
        $studentResult->update([
            'first_ca'=>$request->first_ca,
            'second_ca'=>$request->second_ca,
            'exam'=>$request->exam
        ]);

        $studentResult->updateTotalAndComputeGrade();
        event(new ResultUpdated($studentResult));
        return redirect()->route('dashboard.section.class.subject.result.summary.detail.edit',[$request->studentResultId])->withSuccess('Result Updated');

    }
}
