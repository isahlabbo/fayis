<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fee extends BaseModel
{
    public function feeItems() 
    {
       return $this->hasMany(FeeItem::class); 
    }
}
