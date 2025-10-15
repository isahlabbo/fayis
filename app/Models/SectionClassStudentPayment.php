<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SectionClassStudentPayment extends BaseModel
{
    public function term()
    {
        return $this->belongsTo(Term::class);
    }
    
    public function sectionClassStudent()
    {
        return $this->belongsTo(SectionClassStudent::class);
    }
}
