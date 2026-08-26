<?php

namespace App\Models;

class AdvancePayment extends BaseModel
{
    protected $casts = ['amount'=>'decimal:2', 'applied_amount'=>'decimal:2', 'date'=>'date'];

    public function student() { return $this->belongsTo(Student::class); }
    public function sectionClass() { return $this->belongsTo(SectionClass::class); }
    public function fee() { return $this->belongsTo(Fee::class); }
    public function academicSession() { return $this->belongsTo(AcademicSession::class); }
    public function term() { return $this->belongsTo(Term::class); }
    public function user() { return $this->belongsTo(User::class); }
    public function appliedPayment() { return $this->belongsTo(Payment::class, 'applied_payment_id'); }

    public function getRemainingAmountAttribute()
    {
        return max(0, (float) $this->amount - (float) $this->applied_amount);
    }
}
