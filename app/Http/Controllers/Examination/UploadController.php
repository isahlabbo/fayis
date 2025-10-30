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
