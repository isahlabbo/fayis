<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class InventoryItem extends BaseModel
{
    use SoftDeletes;

    protected $table = 'inventory_items';

    protected $dates = ['deleted_at'];

    public $fillable = [
        'inventory_category_id',
        'sku',
        'name',
        'description',
        'unit_cost',
        'selling_price',
        'quantity',
    ];

    public function category()
    {
        return $this->belongsTo(InventoryCategory::class, 'inventory_category_id');
    }

    public function transactions()
    {
        return $this->hasMany(InventoryTransaction::class, 'inventory_item_id');
    }

    public function stocks()
    {
        return $this->hasMany(InventoryStock::class, 'inventory_item_id');
    }
}
