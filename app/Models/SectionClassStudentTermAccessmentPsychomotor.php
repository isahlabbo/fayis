<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SectionClassStudentTermAccessmentPsychomotor extends BaseModel
{
    public function psychomotor()
    {
        return $this->belongsTo(Psychomotor::class);
    }
    
    public function sectionClassStudentTermAccessment()
    {
        return $this->belongsTo(SectionClassStudentTermAccessment::class);
    }

    public function getPsychomotor()
    {
        if(!$this->psychomotor_id){
            // get random psychomotor id from the student's section psychomotors
            $sectionClassStudentTermAccessment = $this->sectionClassStudentTermAccessment;
            $sectionClassStudentTerm = $sectionClassStudentTermAccessment->sectionClassStudentTerm;
            $sectionClass = $sectionClassStudentTerm->sectionClass;
            $sectionPsychomotors = $sectionClass->psychomotors;
            
            $randomPsychomotor = $sectionPsychomotors->random();
            $this->psychomotor_id = $randomPsychomotor->id;
            $this->save();
        }
        return $this->psychomotor;
    }
}
