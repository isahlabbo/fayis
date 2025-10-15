<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rank extends BaseModel
{
    public function userInvoices()
    {
        return $this->hasMany(UserInvoice::class);
    }
}
