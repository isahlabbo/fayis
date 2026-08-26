<?php

namespace App\Models;

use App\Models\BaseModel;

class InventorySale extends BaseModel
{
    protected $casts = ['usage_date' => 'date'];
    protected $table = 'inventory_sales';

    protected $fillable = [
        'section_class_student_id',
        'total_cost',
        'payment_method',
        'evidence',
        'usage_date',
        'notes',
    ];

    public function saleItems()
    {
        return $this->hasMany(InventorySaleItem::class, 'inventory_sale_id');
    }

    public function student()
    {
        return $this->belongsTo(SectionClassStudent::class, 'section_class_student_id');
    }
}
