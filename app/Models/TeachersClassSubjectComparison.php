<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeachersClassSubjectComparison extends BaseModel
{
    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function sectionClass()
    {
        return $this->belongsTo(SectionClass::class);
    }

    public function term()
    {
        return $this->belongsTo(Term::class);
    }
}
