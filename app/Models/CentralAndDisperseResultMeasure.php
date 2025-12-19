<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CentralAndDisperseResultMeasure extends BaseModel
{
   
    public function subjectTeacherTermlyUpload()
    {
        return $this->belongsTo(SubjectTeacherTermlyUpload::class);
    }


}
