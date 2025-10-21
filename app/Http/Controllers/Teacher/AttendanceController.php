<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SectionClass;
use App\Models\SectionClassStudentTermAccessment;

class AttendanceController extends Controller
{
    public function index($classId) {
        return view('teacher.class.attendance.index',['sectionClass'=>SectionClass::find($classId)]);
    }

    public function update(Request $request, $assessmentId) {
        $assessment = SectionClassStudentTermAccessment::find($assessmentId);
        $assessment->update([
            'days_school_open'=>$request->days_school_open,
            'days_present'=>$request->days_present,
            'days_absent'=>$request->days_absent,
        ]);
    
        return redirect()->route('teacher.class.attendance.index',[$assessment->sectionClassStudentTerm->sectionClassStudent->sectionClass->id])
        ->withSuccess('Attendance Updated');
    }
}
