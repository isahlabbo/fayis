<?php

namespace App\Models;

class MaterialCollection extends BaseModel
{
    public const MATERIALS = [
        'Uniform',
        'Shoes',
        'Books',
        'Sportwears',
        'Sock',
        'Writing Material',
    ];

    protected $casts = ['collected_at' => 'datetime'];

    public function sectionClassStudent()
    {
        return $this->belongsTo(SectionClassStudent::class);
    }

    public function targetSectionClass()
    {
        return $this->belongsTo(SectionClass::class, 'target_section_class_id');
    }

    public function academicSession()
    {
        return $this->belongsTo(AcademicSession::class);
    }

    public function recordedBy()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    public function items()
    {
        return $this->hasMany(MaterialCollectionItem::class);
    }

    public function isFullyCollected()
    {
        return $this->items->isNotEmpty() && $this->items->every(fn ($item) => $item->is_collected);
    }
}
