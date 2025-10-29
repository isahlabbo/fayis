<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SectionClassTeacher;
use App\Models\SectionClass;

class ClassController extends Controller
{
    public function index($classTeacherId){
        return view('teacher.class.index',['classTeacher'=>SectionClassTeacher::find($classTeacherId)]);
    }

    public function students($sectionClassId){
        return view('teacher.class.students',['sectionClass'=>SectionClass::find($sectionClassId)]);
    }

    public function reportCards($sectionClassId){
        return view('teacher.class.reportCards',['sectionClass'=>SectionClass::find($sectionClassId)]);
    }
}
