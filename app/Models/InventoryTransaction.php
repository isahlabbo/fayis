<?php

namespace App\Models;

use App\Models\BaseModel;

class InventoryTransaction extends BaseModel
{
    protected $table = 'inventory_transactions';

    public function item()
    {
        return $this->belongsTo(InventoryItem::class, 'inventory_item_id');
    }
}
