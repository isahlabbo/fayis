<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResultType extends BaseModel
{
    public function sectionClasses()
    {
        return $this->hasMany(SectionClass::class);
    }
}
