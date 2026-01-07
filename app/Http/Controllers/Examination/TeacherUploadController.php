<?php

namespace App\Http\Controllers\Examination;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Teacher;
use App\Models\SubjectTeacherTermlyUpload;

class TeacherUploadController extends Controller
{
    public function index($teacherId) {
        return view('exam.upload.teacher.index',['teacher'=>Teacher::find($teacherId)]);
    }

    public function update(Request $request, $uploadId) {
        $upload = SubjectTeacherTermlyUpload::find($uploadId);
        $upload->update(['status'=>$request->status]);
        return redirect()->route('exam.upload.teacher.index',[$upload->sectionClassSubjectTeacher->teacher->id])->withSuccess('Upload Status Updated');
    }
}
