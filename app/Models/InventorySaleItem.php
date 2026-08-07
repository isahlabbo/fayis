<?php

namespace App\Models;

use App\Models\BaseModel;

class InventorySaleItem extends BaseModel
{
    protected $table = 'inventory_sale_items';

    protected $fillable = [
        'inventory_sale_id',
        'inventory_item_id',
        'quantity',
        'unit_cost',
        'amount',
    ];

    public function sale()
    {
        return $this->belongsTo(InventorySale::class, 'inventory_sale_id');
    }

    public function item()
    {
        return $this->belongsTo(InventoryItem::class, 'inventory_item_id');
    }
}
