<?php

namespace App\Models;


class SectionClassPayment extends BaseModel
{
    public function sectionClass()
    {
        return $this->belongsTo(SectionClass::class);
    }

    public function term()
    {
        return $this->belongsTo(Term::class);
    }

    public function gender()
    {
        return $this->belongsTo(Gender::class);
    }

    public function studentType()
    {
        return $this->belongsTo(StudentType::class);
    }
}
