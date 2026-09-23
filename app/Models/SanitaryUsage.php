<?php

namespace App\Models;

class SanitaryUsage extends BaseModel
{
    protected $casts = ['usage_date' => 'date', 'unit_cost' => 'decimal:2'];

    public function stock() { return $this->belongsTo(SanitaryStock::class, 'sanitary_stock_id'); }
    public function user() { return $this->belongsTo(User::class); }
}
