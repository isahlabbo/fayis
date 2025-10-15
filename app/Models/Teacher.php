<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends BaseModel
{
    public function sectionClassTeachers()
    {
        return $this->hasMany(SectionClassTeacher::class);
    }

    public function sectionClassSubjectTeachers()
    {
        return $this->hasMany(SectionClassSubjectTeacher::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
