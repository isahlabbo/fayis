<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Token extends BaseModel
{
    public function guardian()
    {
        return $this->belongsTo(Guardian::class);
    }

    public function application()
    {
        return $this->hasOne(Application::class);
    }
}
