<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends BaseModel
{
    public function sectionClassSubjects()
    {
        return $this->hasMany(SectionClassSubject::class);
    }

}
