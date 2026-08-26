<?php

namespace App\Models;

use App\Models\BaseModel;

class InventoryStock extends BaseModel
{
    protected $casts = ['received_date' => 'date'];
    protected $table = 'inventory_stocks';

    protected $fillable = [
        'inventory_item_id',
        'quantity',
        'remaining_quantity',
        'unit_cost',
        'unit_selling_price',
        'received_date',
        'notes',
    ];

    public function item()
    {
        return $this->belongsTo(InventoryItem::class, 'inventory_item_id');
    }

    public function saleItems(){ return $this->hasMany(InventorySaleItem::class,'inventory_stock_id'); }
}
