<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use App\Models\Student;
use App\Models\Psychomotor;
use App\Models\AffectiveTrait;
use App\Models\SectionClass;

class StudentAccessmentImport implements ToModel
{
    protected $sectionClass;
    protected $data;

    public function __construct(SectionClass $sectionClass)
    {
        $this->sectionClass = $sectionClass;
    }

    public function model(array $row)
    {
        if($row[0] == 'NAME'){
            $this->data = $row;
        }

        if($row[0] != 'NAME' && isset($row[1])){
            $sectionClassStudentTerm = $this->getThisStudentSessionTerm($row[1]);
            if($sectionClassStudentTerm){
                if(!$row[2]){
                    $row[2] = 0;
                }
                if(!$row[3]){
                    $row[3] = 0;
                }
                if(!$row[4]){
                    $row[4] = 0;
                }
                if(!$row[5]){
                    $row[5] = 1;
                }
                if(!$row[6]){
                    $row[6] = 1;
                }
                
                $section = $this->getStudentSection($sectionClassStudentTerm);
                $accessment = $sectionClassStudentTerm->sectionClassStudentTermAccessment;
                
                if($accessment){
                    $accessment->update([
                        "days_school_open" => $row[2],
                        "days_present" => $row[3],
                        "days_absent" => $row[4],
                        "teacher_comment_id" => $row[5],
                        "head_teacher_comment_id" => $row[6]
                    ]);

                    foreach ($this->data as $key => $value) {
                        $psychomotor = Psychomotor::where(['section_id'=>$section->id,'name'=>$value])->first();
                        if($psychomotor){
                            $psychoAccessment = $accessment->sectionClassStudentTermAccessmentPsychomotors()->firstOrCreate([
                                'psychomotor_id'=>$psychomotor->id
                                ]);
                            $psychoAccessment->update(['value'=>$row[$key] ?? 0]);    
                        }else{
                            $affectiveTrait = AffectiveTrait::where(['section_id'=>$section->id,'name'=>$value])->first();
                            if($affectiveTrait){
                                $traitAccessment = $accessment->sectionClassStudentTermAccessmentAffectiveTraits()->firstOrCreate([
                                   'affective_trait_id'=>$affectiveTrait->id]);
                                $traitAccessment->update(['value'=>$row[$key]]);    
                            }
                        }
                    } 
                }else{
                    
                   $accessment = $sectionClassStudentTerm->sectionClassStudentTermAccessment()->create([
                        "days_school_open" => $row[2],
                        "days_present" => $row[3],
                        "days_absent" => $row[4],
                        "teacher_comment_id" => $row[5],
                        "head_teacher_comment_id" => $row[6]
                        ]);
                        
                        foreach ($this->data as $key => $value) {
                            $psycho = Psychomotor::where(['section_id'=>$section->id,'name'=>$value])->first();
                            if($psycho){
                                $accessment->sectionClassStudentTermAccessmentPsychomotors()->create([
                                    'psychomotor_id'=>$psycho->id,
                                    'value'=>$row[$key],
                                    ]);
                            }

                            $trait = AffectiveTrait::where(['section_id'=>$section->id,'name'=>$value])->first();
                            if($trait){
                                $accessment->sectionClassStudentTermAccessmentAffectiveTraits()->create([
                                    'affective_trait_id'=>$trait->id,
                                    'value'=>$row[$key],
                                    ]);
                            }
                        }    
                }
            }
        }
    }
    
    public function getThisStudentSessionTerm($admissionNo)
    {
        $student = Student::where('admission_no',$admissionNo)->first();
        $studentClass = null;
        if($student){
            $studentClass = $student->sectionClassStudents->where('status','Active')->first();
        }
        if($studentClass){
            return $studentClass->sectionClassStudentTerms->where('status','Active')->first();
        }
        return null;
    }

    public function getStudentSection($sectionClassStudentTerm)
    {
        return $sectionClassStudentTerm->sectionClassStudent->sectionClass->section;
    }
}
