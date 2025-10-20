<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SectionClassSubjectTeacher;

class SubjectTeacherController extends Controller
{
    function index($sectionClassSubjectTeacherId) {
        return view('teacher.subject.index', ['sectionClassSubjectTeacher' => SectionClassSubjectTeacher::find($sectionClassSubjectTeacherId)]);
    }
}
