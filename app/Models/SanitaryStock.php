<?php

namespace App\Models;

class SanitaryStock extends BaseModel
{
    protected $casts = ['received_date' => 'date', 'unit_cost' => 'decimal:2'];

    public function item() { return $this->belongsTo(SanitaryItem::class, 'sanitary_item_id'); }
    public function usages() { return $this->hasMany(SanitaryUsage::class); }
    public function user() { return $this->belongsTo(User::class); }
}
