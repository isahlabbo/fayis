<?php

namespace App\Http\Controllers\Section;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SectionClass;
use App\Models\Student;
use App\Models\Section;
use App\Models\Gender;
use App\Exports\SectionClassStudentExport;
use App\Imports\SectionClassStudentImport;
use App\Services\Upload\FileUpload;
use Excel;

class SectionClassController extends Controller
{
    use FileUpload;

    public function index($sectionClassId)
    {
        return view('section.class.index',['sectionClass'=>SectionClass::find($sectionClassId)]);
    }


    public function register(Request $request, $sectionId)
    {
        $request->validate([
            'class'=>'required|string',
            'class_group'=>'required|string',
            'pass_mark'=>'required|string',
            'year_sequence'=>'required',
            ]);
        $section = Section::find($sectionId);

        foreach($section->sectionClasses->where('name',$request->class) as $sectionClass){
            return redirect()->route('dashboard.section.index',$sectionId)->withWarning('Class has All ready exist');
        }
        $section->sectionClasses()->create([
            'name'=>strtoupper($request->class),
            'section_class_group_id'=>$request->class_group,
            'year_sequence'=>$request->year_sequence,
            'code'=>substr($section->name,0,1),
            'pass_mark'=>$request->pass_mark,
            'section_class_group_id'=>$request->group,
            ]);
        return redirect()->route('dashboard.section.index',$sectionId)->withSuccess('Class Registered');
        
    }

    

    public function updateClass(Request $request, $sectionClassId)
    {
        $request->validate([
            'class'=>'required|string',
            'class_group'=>'required|string',
            'pass_mark'=>'required|string',
            'year_sequence'=>'required',
            'result_type'=>'required',
            ]);
        $class = SectionClass::find($sectionClassId);
        $class->update([
            'name'=>strtoupper($request->class),
            'code'=>substr($class->section->name,0,1),
            
            'section_class_group_id'=> $request->class_group,
            
        ]);
        if($class->year_sequence != $request->year_sequence){
            $class->update(['year_sequence'=> $request->year_sequence]);
        }

        if($class->pass_mark != $request->pass_mark){
            $class->update(['pass_mark'=> $request->pass_mark]);
        }

        if($class->result_type_id != $request->result_type){
            $class->update(['result_type_id'=> $request->result_type]);
        }

        if($class->section_class_group_id != $request->class_group){
            $class->update(['section_class_group_id'=> $request->class_group]);
        }

        return redirect()->route('dashboard.section.view',$class->section->id)->withSuccess('Class Updated');
    }

    public function deleteClass($sectionClassId)
    {
        $class = SectionClass::find($sectionClassId);
        if(count($class->sectionClassStudents)==0){
            $class->delete();
            return redirect()->route('dashboard.section.index',$class->section->id)->withSuccess('Class Deleted');            
        }else{
            return redirect()->route('dashboard.section.index',$class->section->id)->withSuccess('Sorry this class cant be deleted, it has some student inside');
        }
    }

    public function download($sectionClassId)
    {
        $sectionClass = SectionClass::find($sectionClassId);
        return Excel::download(new SectionClassStudentExport($sectionClass), strtolower(str_replace(' ','_',$sectionClass->name)).'_'.str_replace('/','_',$sectionClass->currentSession()->name).'_admitted_list.xlsx');
    }

    public function upload(Request $request, $sectionClassId)
    {
        
        $request->validate([
            'template'=>'required',
        ]);
        $sectionClass = SectionClass::find($sectionClassId);
        Excel::import(new SectionClassStudentImport($sectionClass), request()->file('template'));

        return redirect()->route('dashboard.section.class.student',[$sectionClassId])->withSuccess('All Students Uploaded');
    }


    public function student($sectionClassId)
    {
        return view('section.class.student.index',['sectionClass'=>SectionClass::find($sectionClassId)]);
    }

    public function edit($studentId)
    {
        return view('section.class.student.edit',['genders'=>Gender::all(),'sections'=>Section::all(),'student'=>Student::find($studentId)]);
    }

    public function letter($studentId)
    {
        return view('section.class.student.letter',['genders'=>Gender::all(),'sections'=>Section::all(),'student'=>Student::find($studentId)]);
    }

    public function delete($studentId)
    {
        $student = Student::find($studentId);
        foreach($student->sectionClassStudents as $sectionClassStudent){
            $sectionClassStudent->delete();
        }

        $student->delete();

        return back()->withSuccess('Student Deleted Successfully');
    }

    public function update(Request $request, $studentId)
    {
        $sectionClass = SectionClass::find($request->class);
        $student = Student::find($studentId);
        $guardian = $student->guardian;
        $guardian->update([
            'name'=>strtoupper($request->guardian_name),
            'phone'=>$request->phone,
            'email'=>$request->email,
            'address'=>$request->address
        ]);
        $sectionClass = $student->activeSectionClass();
        $sectionClassStudent = $student->sectionClassStudents->where('status', 'Active')->first();
       
        if($request->section){
            
            $sectionClassStudent->update(['status'=>'Not Active']);
            foreach($sectionClassStudent->sectionClassStudentTerms as $sectionClassStudentTerm){
                $sectionClassStudentTerm->update(['status'=>'Not Active']);
            }
            // create new section student
            $sectionClass = SectionClass::find($request->class);
            $sectionClass->reserveNumber($student->admission_no);
            $newStudent = $guardian->students()->create([
                'name'=>strtoupper($request->name),
                'date_of_birth'=>$request->date_of_birth,
                'admission_no'=>$sectionClass->generateAdmissionNo(),
                'section_class_id'=>$request->class,
                'academic_session_id'=>$sectionClass->classAdmissionSession()->id,
                'gender_id'=>$request->gender,
                'picture'=>$student->picture
            ]);
            // assign him to the new section class
            $newStudent->assignToThisClass($sectionClass->id,'Active');
        }else{
            
            if($request->class){
                if(count($sectionClassStudent->uploadedResult()) > 0){
                    $sectionClassStudent->update(['status'=>'Not Active']);
                    foreach($sectionClassStudent->sectionClassStudentTerms as $sectionClassStudentTerm){
                        $sectionClassStudentTerm->update(['status'=>'Not Active']);
                    }
                }else{
                    foreach($sectionClassStudent->sectionClassStudentTerms as $sectionClassStudentTerm){
                        $sectionClassStudentTerm->delete();
                    }
                    $sectionClassStudent->delete();
                }
                $student->assignToThisClass($request->class,'Active'); 
            }
        }


        if($request->picture){
            $this->updateFile($student,'picture',$request->picture,$sectionClass->section->name.'/'
            .$sectionClass->name.'/'
            .str_replace('/','-',$sectionClass->currentSession()->name)
            ."/Admission/");
        }

        $student->update([
            'name'=>strtoupper($request->name),
            'date_of_birth'=>$request->date_of_birth,
            'section_class_id'=>$request->class,
            'gender_id'=>$request->gender
        ]);
        if(!$request->section || !$request->class){
            $student->update([
                'admission_no'=>$request->admission_no,
            ]);
        }
        
        return redirect()->route('dashboard.section.class.student',[$sectionClass->id])
        ->withSuccess('Updated');
        
    }

    public function reGenerateAdmissionNo($sectionClassId)
    {
        $sectionClass = SectionClass::find($sectionClassId);
        $sectionClass->updateAdmissionNo();
        return back()->withSuccess('All admission number regenerated');
    }

    public function promotion($sectionClassId)
    {
        $class = SectionClass::find($sectionClassId);
        foreach ($class->sectionClassStudents
            ->where('status','Active')->where('academic_session_id',$class->currentSession()->id) as $sectionClassStudent) {
                
            $sectionClassStudent->promoteToNextClass();
        }
        return back()->withSuccess('Promotion was Successfully Compilled');
    }
}
