<?php

namespace App\Http\Controllers\Section;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\SectionClass;
use App\Models\SectionClassSubject;
use App\Models\AcademicSessionTerm;
use App\Models\SubjectTeacherTermlyUpload;

class SubjectController extends Controller
{
    public function index($sectionClassId)
    {
        return view('section.class.subject.index',['sectionClass'=>SectionClass::find($sectionClassId)]);
    }

    public function result($sectionClassId)
    {
        return view('section.class.subject.result.index',['sectionClass'=>SectionClass::find($sectionClassId)]);
    }

    public function register(Request $request, $sectionClassId)
    {
        $sectionClass = SectionClass::find($sectionClassId);
        foreach($request->all() as $subjectId => $subjectName){
            $subject = Subject::find($subjectId);
            if($subject){
                $subjectClass = $sectionClass->sectionClassSubjects()->firstOrCreate(['name'=>strtoupper($subject->name),'subject_id'=>$subject->id]);
                $subjectClass->sectionClassSubjectTeachers()->create([
                    'teacher_id'=>rand(1,count(Teacher::all()))
                ]);
            }
        }

        return redirect()->route('section.class.subject.index',[$sectionClassId])
            ->withSuccess('Class Subject Registered');
       
    }

    public function update(Request $request, $sectionClassId, $sectionClassSubjectId)
    {
        $sectionClass = SectionClass::find($sectionClassId);
        if(count($sectionClass->sectionClassSubjects->where('name',$request->name)) > 0){
            return redirect()->route('section.class.subject.index',[$sectionClassId])
            ->withwarning('Class Subject already exist');
        }else{
            $subject = Subject::firstOrCreate(['name'=>strtoupper($request->name)]);
            $sectionClassSubject = SectionClassSubject::find($sectionClassSubjectId);
            $sectionClassSubject->update(['name'=>strtoupper($request->name),'subject_id'=>$subject->id]);
            return redirect()->route('section.class.subject.index',[$sectionClassId])
            ->withSuccess('Class Subject Updated');
        }
    }

    public function delete($sectionClassId, $sectionClassSubjectId)
    {
        $sectionClassSubject = SectionClassSubject::find($sectionClassSubjectId);
        if(count($sectionClassSubject->availableResultUploads())>0){
            return redirect()->route('section.class.subject.index',[$sectionClassId])
            ->withwarning('Sorry we cant delete this subject there are result uploade on it');
        }else{
            $sectionClassSubject->delete();
            return redirect()->route('section.class.subject.index',[$sectionClassId])
            ->withSuccess('Class Subject Deleted');
        }
    }

    public function termResult ($classId, $subjectId, $termId)
    {
        return view('section.class.subject.upload',['termId'=>$termId, 'sectionClassSubject'=>SectionClassSubject::find($subjectId)]);
    }

    public function updateUpload (Request $request, $classId, $subjectId, $termId, $uploadId)
    {
        $upload = SubjectTeacherTermlyUpload::find($uploadId);
        $sessionTerm = AcademicSessionTerm::find($request->academic_session_term_id);
        $upload->update(['term_id'=>$sessionTerm->term->id,
        'academic_session_term_id'=>$request->academic_session_term_id]);
        return redirect()->route('section.class.subject.termResult',[
            $classId, $subjectId, $termId])->withSuccess('Upload updated');
    }

    public function updateResult ($classId, $subjectId, $termId, $uploadId)
    {
        return view('section.class.subject.result',['upload'=>SubjectTeacherTermlyUpload::find($uploadId)]);
    }
}
