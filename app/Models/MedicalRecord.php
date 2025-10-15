<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalRecord extends BaseModel
{
    public function application()
    {
        return $this->belongsTo(MedicalRecord::class);
    }
}
