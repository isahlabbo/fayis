<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SectionClassReservedAdmissionNo extends BaseModel
{
    public function academicSession()
    {
        return $this->belongsTo(AcademicSession::class);
    }

    public function sectionClass()
    {
        return $this->belongsTo(SectionClass::class);
    }
}
