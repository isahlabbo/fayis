<?php

namespace App\Models;

use App\Models\BaseModel;

class InventoryUsage extends BaseModel
{
    protected $table = 'inventory_usages';

    protected $fillable = [
        'inventory_item_id',
        'inventory_stock_id',
        'section_class_student_id',
        'teacher_id',
        'usage_type',
        'quantity',
        'unit_cost',
        'total_cost',
        'receipt_number',
        'evidence',
        'usage_date',
        'notes',
    ];

    public function item()
    {
        return $this->belongsTo(InventoryItem::class, 'inventory_item_id');
    }

    public function student()
    {
        return $this->belongsTo(SectionClassStudent::class, 'section_class_student_id');
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }

    public function stock()
    {
        return $this->belongsTo(InventoryStock::class, 'inventory_stock_id');
    }
}
