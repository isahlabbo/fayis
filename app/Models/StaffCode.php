<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffCode extends BaseModel
{
    public function staff()
    {
        return $this->hasOne(Staff::class);
    }
}
