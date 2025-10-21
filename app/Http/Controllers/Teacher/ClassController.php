<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SectionClassTeacher;

class ClassController extends Controller
{
    public function index($classTeacherId){
        return view('teacher.class.index',['classTeacher'=>SectionClassTeacher::find($classTeacherId)]);
    }
}
