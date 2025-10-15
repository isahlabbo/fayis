<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SectionClassTeacher extends BaseModel
{
    public function sectionClass()
    {
        return $this->belongsTo(SectionClass::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }
    
}
