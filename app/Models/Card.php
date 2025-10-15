<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Card extends BaseModel
{
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
