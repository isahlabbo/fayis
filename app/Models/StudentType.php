<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentType extends BaseModel
{
    public function sectionClassPayments()
    {
        return $this->hasMany(SectionClassPayment::class);
    }

    public function student()
    {
        return $this->hasMany(Student::class);
    }
}
