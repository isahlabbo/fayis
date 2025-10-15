<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SectionStudentGraduation extends BaseModel
{
    public function academicSession()
    {
        return $this->belongsTo(AcademicSession::class);
    }
    
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
    
    public function section()
    {
        return $this->belongsTo(Section::class);
    }
}
