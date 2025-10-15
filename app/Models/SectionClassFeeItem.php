<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SectionClassFeeItem extends BaseModel
{
    public function sectionClassFee() 
    {
        return $this->belongsTo(SectionClassFee::class);
    }

    public function term() 
    {
        return $this->belongsTo(Term::class);
    }

    public function gender() 
    {
        return $this->belongsTo(Gender::class);
    }
}
