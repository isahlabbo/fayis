<?php

namespace App\Models;

use App\Models\BaseModel;

class InventoryCategory extends BaseModel
{
    protected $table = 'inventory_categories';

    public function items()
    {
        return $this->hasMany(InventoryItem::class, 'inventory_category_id');
    }
}
