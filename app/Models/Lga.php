<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lga extends BaseModel
{
    public function state()
    {
        return $this->belongsTo(State::class);
    }
    
}
