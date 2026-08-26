<?php

namespace App\Models;

use App\Models\BaseModel;

class InventorySaleItem extends BaseModel
{
    protected $table = 'inventory_sale_items';

    protected $fillable = [
        'inventory_sale_id',
        'inventory_item_id',
        'inventory_stock_id',
        'quantity',
        'unit_cost',
        'cost_price',
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

    public function stock(){ return $this->belongsTo(InventoryStock::class,'inventory_stock_id'); }

    public function getProfitAttribute(){ return ($this->unit_cost-$this->cost_price)*$this->quantity; }
}
