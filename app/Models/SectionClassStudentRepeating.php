<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SectionClassStudentRepeating extends Model
{
    public function academicSession()
    {
        return $this->belongsTo(AcademicSession::class);
    }

    public function sectionClassStudent()
    {
        return $this->belongsTo(SectionClassStudent::class);
    }
    
}
