<?php

namespace App\Http\Controllers\Section;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Term;
use App\Models\GradeScale;
use App\Models\RemarkScale;

class StudentResultController extends Controller
{
    public function view($studentId)
    {
        return view('section.class.student.result.view',['gradeScales'=>GradeScale::all(),'remarkScales'=>RemarkScale::all(),'terms'=>Term::all(),'student'=>Student::find($studentId)]);
    }
}
