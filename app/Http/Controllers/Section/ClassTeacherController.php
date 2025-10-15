<?php

namespace App\Http\Controllers\Section;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SectionClass;
use App\Models\SectionClassTeacher;
use App\Models\Teacher;

class ClassTeacherController extends Controller
{
    public function create($sectionClassId)
    {
        return view('section.class.classTeacher.create',['teachers'=>Teacher::all(),'sectionClass'=>SectionClass::find($sectionClassId)]);
    }

    public function register(Request $request, $sectionClassId)
    {

        $request->validate([
            'teacher' => 'required'
        ]);
        
        $teacher = Teacher::find($request->teacher);

        $sectionClass = SectionClass::find($request->sectionClassId);

        if(isset($request->change)){
            foreach($sesctionClass->sectionClassTeachers->where('status','Active') as $sectionClassTeacher){
                $sectionClassTeacher->update(['status','Not Active']);
            }
            $message = 'Class Teacher changed successfully';
        }else{
           $message = 'Class Teacher Assigned successfully';
        }

        $teacher->sectionClassTeachers()->create(['section_class_id'=>$request->sectionClassId]);

        return redirect()->route('section.class.index',$sectionClass->id)->withSuccess($message);
    }


    public function reCreate($sectionClassTeacherId)
    {
        return view('section.class.classTeacher.reCreate',['teachers'=>Teacher::all(),'sectionClassTeacher'=>SectionClassTeacher::find($sectionClassTeacherId)]);
    }
}
