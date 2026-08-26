<?php

namespace App\Models;

use App\Models\BaseModel;

class InventoryRent extends BaseModel
{
    protected $table = 'inventory_rents';

    protected $fillable = [
        'inventory_item_id',
        'teacher_id',
        'quantity',
        'usage_date',
        'notes',
        'academic_session_id', 'returned_quantity', 'returned_at', 'status',
    ];

    public function item()
    {
        return $this->belongsTo(InventoryItem::class, 'inventory_item_id');
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }

    public function academicSession(){ return $this->belongsTo(AcademicSession::class); }
}
