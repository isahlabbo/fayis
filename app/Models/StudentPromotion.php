<?php

namespace App\Models;

class StudentPromotion extends BaseModel
{
    protected $dates = ['cancelled_at'];

    public function student() { return $this->belongsTo(Student::class); }
    public function fromEnrolment() { return $this->belongsTo(SectionClassStudent::class, 'from_enrolment_id'); }
    public function toEnrolment() { return $this->belongsTo(SectionClassStudent::class, 'to_enrolment_id'); }
    public function promotedBy() { return $this->belongsTo(User::class, 'promoted_by'); }
    public function cancelledBy() { return $this->belongsTo(User::class, 'cancelled_by'); }
}
