<?php

namespace App\Http\Controllers\Section;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Section;
use App\Models\Guardian;
use App\Models\SectionClass;
use App\Services\Upload\FileUpload;

class StudentController extends Controller
{
    use FileUpload;

    public function index()
    {
       return view('admission.student.index');
    }
    

    public function search(Request $request)
    {
        $sectionClass = SectionClass::find($request->class);
        
        return redirect()->route('admission.student.view',[$sectionClass->id]);
        
    }

    public function view($sectionClassId)
    {
       return view('admission.student.view',['sectionClass'=>SectionClass::find($sectionClassId)]);
    }

    public function create($sectionClassId)
    {
       return view('admission.student.create',['sectionClass'=>SectionClass::find($sectionClassId)]);
    }

    public function edit($studentId)
    {

       return view('admission.student.edit',['student'=>Student::find($studentId)]);
    }


    public function resume($academicSessionTermId)
    {
        return view('school.student.resume',['academicSessionTerm'=>AcademicSessionTerm::find($academicSessionTermId)]);
    }

    public function confirmResume ($academicSessionTermId)
    {
        foreach(Section::cursor() as $section){
            foreach($section->sectionClasses as $sectionClass){
                $sectionClass->updateAllStudentTerm();
            }
        }
        
        $academicSessionTerm = AcademicSessionTerm::find($academicSessionTermId);
        $academicSessionTerm->academicSession->updateNextTerm($academicSessionTerm->term);
        return redirect()->route('dashboard')->withSuccess('All Students Info has been updated to the next term');
    }

    public function delete($studentId) {

        $student = Student::find($studentId);
       
        foreach($student->sectionClassStudents() as $sectionClassStudent){
            foreach($sectionClassStudent->sectionClassStudentTerms as $sectionClassStudentTerm){
                $sectionClassStudentTerm->delete();
            }
            $sectionClassStudent;
        }
        

        return redirect()->route('admission.student.index')->withSuccess('Student Information Deleted');
    }

    public function register(Request $request, $sectionClassId)
    {

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:255'],
            'date_of_birth' => ['required'],
        ]);
        $sectionClass = SectionClass::find($sectionClassId);
        
        $guardian = Guardian::where('phone',$request->phone)->first();
        if(!$guardian){
            $guardian = Guardian::create([
                'name'=>strtoupper($request->guardian_name),
                'phone'=>$request->phone,
                'email'=>$request->email,
                'address'=>$request->address
            ]);
        }
        

        $student = $guardian->students()->create([
            'name'=>strtoupper($request->name),
            'date_of_birth'=>$request->date_of_birth,
            'admission_no'=>$sectionClass->generateAdmissionNo(),
            'academic_session_id'=>$sectionClass->classAdmissionSession()->id || $sectionClass->currentSession(),
            'gender_id'=>$request->gender
        ]);

        if($request->picture){
            $this->storeFile($student,'picture',$request->picture,
            $sectionClass->section->name.'/'
            .$sectionClass->name.'/'
            .str_replace('/','-',$sectionClass->currentSession()->name)
            ."/Admission/");
        }

        $classStudent = $student->sectionClassStudents()->create([
            'section_class_id'=>$sectionClass->id, 
            'academic_session_id'=>$sectionClass->classAdmissionSession()->id || $sectionClass->currentSession(),
        ]);
        
        
        foreach($student->currentSession()->academicSessionTerms as $academicSessionTerm){
            if($academicSessionTerm->status == 'Active'){
                $academicSessionTerm->sectionClassStudentTerms()->create(['status'=>'Active','section_class_student_id'=>$classStudent->id]);
            }else{
                $academicSessionTerm->sectionClassStudentTerms()->create(['section_class_student_id'=>$classStudent->id]);
            }
        }

        return redirect()->route('admission.student.view',[$sectionClass->id])->withSuccess('Student Registered Successfully');
    }

    public function update(Request $request, $studentId)
    {

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:255'],
            'date_of_birth' => ['required'],
            'class' => ['required']
        ]);
        $student = Student::find($studentId);
        
        
            $student->guardian->update([
                'name'=>strtoupper($request->guardian_name),
                'phone'=>$request->phone,
                'email'=>$request->email,
                'address'=>$request->address
            ]);
       
        

        $student->update([
            'name'=>strtoupper($request->name),
            'date_of_birth'=>$request->date_of_birth,
            'admission_no'=>$request->admission_no,
            'gender_id'=>$request->gender
        ]);

        if($request->picture){
            $this->storeFile($student,'picture',$request->picture,
            $sectionClass->section->name.'/'
            .$sectionClass->name.'/'
            .str_replace('/','-',$sectionClass->currentSession()->name)
            ."/Admission/");
        }

        $classStudent = $student->sectionClassStudents()->firstOrCreate([
            'section_class_id'=>$request->class, 
        ]);
        
        
        foreach($student->currentSession()->academicSessionTerms as $academicSessionTerm){
            if($academicSessionTerm->status == 'Active'){
                $academicSessionTerm->sectionClassStudentTerms()->firstOrCreate(['status'=>'Active','section_class_student_id'=>$classStudent->id]);
            }else{
                $academicSessionTerm->sectionClassStudentTerms()->firstOrCreate(['section_class_student_id'=>$classStudent->id]);
            }
        }

        return redirect()->route('admission.student.view',[$classStudent->sectionClass->id])->withSuccess('Student Updated Successfully');
    }
}
