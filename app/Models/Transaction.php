<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends BaseModel
{
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function card()
    {
        return $this->belongsTo(Card::class);
    }

    public function paid()
    {
        return $this->charged_amount - ($this->charged_amount/100)*2;
    }

    public function createdToday()
    {
        return (date('Y-M-d',time()) == date('Y-M-d',strtotime($this->created_at)));
    }
}
