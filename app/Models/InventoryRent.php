<?php

namespace App\Models;

use App\Models\BaseModel;

class InventoryRent extends BaseModel
{
    protected $table = 'inventory_rents';

    protected $fillable = [
        'inventory_item_id',
        'teacher_id',
        'quantity',
        'usage_date',
        'notes',
    ];

    public function item()
    {
        return $this->belongsTo(InventoryItem::class, 'inventory_item_id');
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }
}
