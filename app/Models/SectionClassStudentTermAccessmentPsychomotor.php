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
}
