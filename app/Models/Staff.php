<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Staff extends BaseModel
{
    public function guardian()
    {
        return $this->belongsTo(Guardian::class);
    }

    public function gender()
    {
        return $this->belongsTo(Gender::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
