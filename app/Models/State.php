<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class State extends BaseModel
{
    public function lgas()
    {
        return $this->hasMany(Lga::class);
    }

    public function neighboringStateDiscounts()
    {
        return $this->hasMany(NeighboringStateDiscount::class);
    }

    public function discount(Term $term, Section $section)
    {
        
        $discount = 0;
        foreach($this->neighboringStateDiscounts->where('term_id',$term->id)->where('section_id',$section->id) as $stateDiscount){
            $discount = $stateDiscount->amount;
        }
        return $discount;
    }
}
