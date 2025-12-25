<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SectionClassStudentTermAccessmentAffectiveTrait extends BaseModel
{
    public function affectiveTrait()
    {
        return $this->belongsTo(AffectiveTrait::class);
    }
    
    public function sectionClassStudentTermAccessment()
    {
        return $this->belongsTo(SectionClassStudentTermAccessment::class);
    }
    public function getAffectiveTrait()
    {
        if(!$this->affective_trait_id){
            // get random affective trait id from all affective traits
            $randomAffectiveTrait = AffectiveTrait::all()->random();
            $this->affective_trait_id = $randomAffectiveTrait->id;
            $this->save();
        }
        return $this->affectiveTrait;
    }
}
