<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lga;
use App\Models\SectionClass;
use App\Models\SectionClassSubject;

class AjaxController extends Controller
{
    public function getSectionClasses($sectionId)
    {
        return response()->json(SectionClass::where('section_id',$sectionId)->pluck('name','id'));
    }

    public function getClassStudents($classId)
    {
        $students = [];
        $class = SectionClass::find($classId);
        if($class){
            foreach($class->sectionClassStudents as $sectionClassStudent){
                $students[] = ['name'=>$sectionClassStudent->student->name,'id'=>$sectionClassStudent->id];
            }
        }
        return response()->json($students);
    }

    public function getClassSubjects($sectionClassId)
    {
        return response()->json(SectionClassSubject::where(['section_class_id'=>$sectionClassId,'status'=>'Active'])->pluck('name','id'));
    }


    public function getLgas($stateId)
    {
        return response()->json(Lga::where(['state_id'=>$stateId])->pluck('name','id'));
    }

    
    
}
