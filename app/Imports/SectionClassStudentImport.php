<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use App\Models\SectionClassStudent;
use App\Models\SectionClass;
use App\Models\Student;
use App\Models\State;

class SectionClassStudentImport implements ToModel
{
    protected $sectionClass;

    public function __construct(SectionClass $classClass)
    {
        $this->sectionClass = $classClass;
    }

    /**
    * @param Collection $collection
    */
    public function model(array $row)
    {
        // student name = $row[0];
        // guadian name = $row[1];
        // student gender = $row[2];
        // student date_of_birth = $row[3];
        foreach($this->sectionClass->sectionClassStudents as $sectionClassStudent){
            foreach($sectionClassStudent->sectionclassStudentTerms as $studentTerm){
                $studentTerm->delete();
            }
            $sectionClassStudent->student->delete();
            $sectionClassStudent->delete();
        }

        if($row[0] != 'Student Name' && $row[0] != ''){
                $guardian = Guardian::firstOrCreate('phone',$row[4])->first();
                
                $student = $guardian->students()->create([
                    'name'=>strtoupper($row[0]),
                    'date_of_birth'=>$row[1],
                    'admission_no'=>$row[2],
                    'gender_id'=>$row[3],
                    'academic_session_id'=>$this->sectionClass->classAdmissionSession()->id,
                ]);

                $classStudent = $student->sectionClassStudents()->create(['section_class_id'=>$this->sectionClass->id]);
               
                $this->generateAndUpdateProfileCode($classStudent);
                $count = 1;
                foreach($student->currentSession()->academicSessionTerms as $academicSessionTerm){
                    if($count == 1){
                        $academicSessionTerm->sectionClassStudentTerms()->create(['status'=>'Active','section_class_student_id'=>$classStudent->id]);
                    }else{
                        $academicSessionTerm->sectionClassStudentTerms()->create(['section_class_student_id'=>$classStudent->id]);
                    }
                    $count++;
                }
            
        }
   
    }

    
    public function getLgaId($stateName)
    {
        
        if($stateName){
            $state = State::where('name',$stateName)->first();
            if($state){
                $first = $state->lgas[1]->id;
                $last = $first + count($state->lgas)-1;
                return rand($first, $last);
            }
            
        }
        
    }

    public function generateAndUpdateProfileCode(SectionClassStudent $sectionClassStudent)
    {
        
        $codeExt = substr($sectionClassStudent->sectionClass->section->name,0,1);
        $year = date('y') - $this->getNumberSequence($sectionClassStudent->sectionClass->year_sequence);
        $serialNo = sprintf('%04d', count($sectionClassStudent->sectionClass->section->students()));
        $code = $codeExt.$year.$serialNo;
        $sectionClassStudent->student->update(['profile_code'=>$code]);
    }

    public function getNumberSequence($sequence){

        switch ($sequence) {
            case 'First':
                $number = 1;
                break;
            case 'Second':
                $number = 2;
                break;
            case 'Third':
                $number = 3;
                break;
            case 'Forth':
                $number = 4;
                break;
            case 'Fifth':
                $number = 5;
                break;
            default:
                $number = 6;
                break;
        }
        return $number;
    }
}
