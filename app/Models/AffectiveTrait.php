<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AffectiveTrait extends BaseModel
{
    public function section()
    {
        return $this->belongsTo(Section::class);
    }
}
