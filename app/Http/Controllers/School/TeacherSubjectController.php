<?php

namespace App\Http\Controllers\School;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Teacher;
use App\Models\SectionClassSubject;
use App\Models\SectionClassSubjectTeacher;

class TeacherSubjectController extends Controller
{
    public function index($teacherId){
        return view('school.teacher.subjects.index',['teacher'=>Teacher::find($teacherId)]);
    }

    public function remove($teacherId, $sectionClassSubjectTeacherId) {
        $subjectTeacher = SectionClassSubjectTeacher::find($sectionClassSubjectTeacherId);
        if(count($subjectTeacher->subjectTeacherTermlyUploads) > 0){
            foreach($subjectTeacher->subjectTeacherTermlyUploads as $upload){
                foreach($upload->studentResults as $result){
                    $result->delete();
                }
                $upload->delete();
            }
        }
        $subjectTeacher->delete();

        return redirect()->route('administration.teacher.subject.index',[$teacherId])->withSuccess('Subject Removed');
    }

    public function add(Request $request, $teacherId) {
        $request->validate([
            'class'=>'required',
            'subject'=>'required',
        ]);

        $teacher = Teacher::find($request->teacherId);

        $sectionClassSubject = SectionClassSubject::find($request->subject);
        
        if(count($sectionClassSubject->sectionClassSubjectTeachers) > 0){
            foreach($sectionClassSubject->sectionClassSubjectTeachers as $sectionClassSubjectTeacher){
                $sectionClassSubjectTeacher->update(['status'=>'Not Active']);
            }
            $message = 'All previous allocation to this subject was revoke and Subject Added to this teacher';
        }else{
            $message = 'Subject Added';
        }

        $teacher->sectionClassSubjectTeachers()->create([
            'section_class_subject_id'=>$request->subject
        ]);

        return redirect()->route('administration.teacher.subject.index',[$teacherId])
        ->withSuccess($message);

    }
}
