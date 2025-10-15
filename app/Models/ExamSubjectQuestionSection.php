<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamSubjectQuestionSection extends BaseModel
{
    public function sectionClassTermlyExam()
    {
       return $this->belongsTo(SectionClassTermlyExam::class);
    }

    public function sectionClassSubject()
    {
       return $this->belongsTo(SectionClassSubject::class);
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }
}
