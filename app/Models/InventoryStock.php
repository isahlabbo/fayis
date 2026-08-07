<?php

namespace App\Models;

use App\Models\BaseModel;

class InventoryStock extends BaseModel
{
    protected $table = 'inventory_stocks';

    protected $fillable = [
        'inventory_item_id',
        'quantity',
        'remaining_quantity',
        'unit_cost',
        'received_date',
        'notes',
    ];

    public function item()
    {
        return $this->belongsTo(InventoryItem::class, 'inventory_item_id');
    }
}
