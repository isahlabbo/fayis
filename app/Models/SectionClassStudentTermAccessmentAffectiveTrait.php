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
}
