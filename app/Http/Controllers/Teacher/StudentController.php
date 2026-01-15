<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Services\Upload\FileUpload;

class StudentController extends Controller
{
    use FileUpload;

    public function index($classId)
    {
        $sectionClass = \App\Models\SectionClass::find($classId);
        return view('teacher.class.student.index', compact('sectionClass'));
    }
    public function update(Request $request, $studentId)
    {
        $request->validate([
            'picture' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:1024',
        ]);

        $student = \App\Models\Student::find($studentId);
        // store student picture
        $file = $request->file('picture');
        $location = 'profile/student/';
        
        if($student->picture)
            $this->updateFile($student, 'picture', $file, $location); 
        else    
            $this->storeFile($student, 'picture', $file, $location); 

        return redirect()->back()->with('success', 'Student picture uploaded.');
    }
}
