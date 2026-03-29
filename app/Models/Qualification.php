<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Qualification extends BaseModel
{
    public function teacher() {
        return $this->belongsTo(Teacher::class);
    }

    public function viewQualification()
    {
        return Storage::url($this->file);
    }
}
