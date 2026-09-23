<?php

namespace App\Models;

class MaterialCollectionItem extends BaseModel
{
    protected $casts = ['is_collected' => 'boolean', 'collected_at' => 'datetime'];

    public function materialCollection()
    {
        return $this->belongsTo(MaterialCollection::class);
    }

    public function inventoryItem()
    {
        return $this->belongsTo(InventoryItem::class);
    }
}
