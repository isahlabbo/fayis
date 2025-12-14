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
        if($request->days_school_open == ($request->days_present+$request->days_absent)){
        $assessment->update([
            'days_school_open'=>$request->days_school_open,
            'days_present'=>$request->days_present,
            'days_absent'=>$request->days_absent,
        ]);
    
        return redirect()->route('teacher.class.attendance.index',[$assessment->sectionClassStudentTerm->sectionClassStudent->sectionClass->id])
        ->withSuccess('Attendance Updated');
        }
        return redirect()->back()->withError('Invalid Attendance Record: Days prsent + Days Absent must be equal to Days School Open');
    }
}
