<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SectionClassStudentTermAccessment extends BaseModel
{
    public function sectionClassStudentTerm()
    {
        return $this->belongsTo(SectionClassStudentTerm::class);
    }

    public function headTeacherComment()
    {
        return $this->belongsTo(HeadTeacherComment::class);
    }

    public function teacherComment()
    {
        return $this->belongsTo(TeacherComment::class);
    }

    public function sectionClassStudentTermAccessmentAffectiveTraits()
    {
        return $this->hasMany(SectionClassStudentTermAccessmentAffectiveTrait::class);
    }

    public function sectionClassStudentTermAccessmentPsychomotors()
    {
        return $this->hasMany(SectionClassStudentTermAccessmentPsychomotor::class);
    }
}
