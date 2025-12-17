<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TermlyTeacherEffectiveIndex extends BaseModel
{
    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function term()
    {
        return $this->belongsTo(Term::class);
    }
}
