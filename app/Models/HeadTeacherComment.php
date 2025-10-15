<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HeadTeacherComment extends BaseModel
{
    public function sectionClassStudentTermAccessments()
    {
        return $this->hasMany(SectionClassStudentTermAccessment::class);
    }
}
