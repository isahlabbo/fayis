<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SectionClassFee extends BaseModel
{
    public function fee()
    {
       return $this->belongsTo(Fee::class); 
    }

    public function sectionClass()
    {
       return $this->belongsTo(SectionClass::class); 
    }

    public function sectionClassFeeItems() 
    {
        return $this->hasMany(SectionClassFeeItem::class);
    }

    public function payments() 
    {
        return $this->hasMany(Payment::class);
    }
}
