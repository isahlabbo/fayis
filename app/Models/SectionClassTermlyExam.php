<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SectionClassTermlyExam extends BaseModel
{
    public function academicSession()
    {
        return $this->belongsTo(AcademicSession::class);
    }

    public function sectionClass()
    {
        return $this->belongsTo(SectionClass::class);
    }

    public function academicSessionTerm()
    {
        return $this->belongsTo(AcademicSessionTerm::class);
    }

    public function examSubjectQuestionSections()
    {
        return $this->hasMany(ExamSubjectQuestionSection::class);
    }
}
