<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Term extends BaseModel
{
    public function sectionClassPayments()
    {
        return $this->hasMany(SectionClassPayment::class);
    }

    public function sectionClassStudentPayments()
    {
        return $this->hasMany(SectionClassStudentPayment::class);
    }

    public function subjectTeacherTermylUpload()
    {
        return $this->hasMany(SubjectTeacherTermlyUpload::class);
    }
}
