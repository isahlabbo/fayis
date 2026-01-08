<?php

namespace App\Http\Controllers\Examination;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SectionClass;
use App\Models\Section;
use App\Models\SubjectTeacherTermlyUpload;

class UploadController extends Controller
{
    public function index($sectionId) {
        return view('exam.upload.index',['section'=>Section::find($sectionId)]);
    }

    public function report() {
        return view('exam.upload.report');
    }

    public function classReportShow($sectionClassId) {
        return view('exam.upload.class_report',['sectionClass'=>SectionClass::find($sectionClassId)]);
    }

    public function classReport() {
        return view('exam.upload.class');
    }
    
    public function teacherReport($teacherId) {
        return view('exam.upload.teacher_report',['teacher'=>Teacher::find($teacherId)]);
    }
    
    public function summary($sectionClassId) {
        return view('exam.upload.summary',['sectionClass'=>SectionClass::find($sectionClassId)]);
    }

    public function details($uploadId) {
        return view('exam.upload.details',['subjectTeacherTermlyUpload'=>SubjectTeacherTermlyUpload::find($uploadId)]);
    }

    public function returnForCorrection($uploadId) {
        $upload = SubjectTeacherTermlyUpload::find($uploadId);
        $upload->update(['status'=>0]);
        return redirect()->route('exam.upload.summary',[$upload->sectionClassSubjectTeacher->sectionClassSubject->sectionClass->id])->withSuccess('Upload Returned for Correction');
    }
}
