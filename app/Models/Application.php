<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Application extends BaseModel
{
    public function educationalRecords()
    {
        return $this->hasMany(EducationalRecord::class);
    }

    public function quranKnowledges()
    {
        return $this->hasMany(QuranKnowledge::class);
    }

    public function languageProfineciencies()
    {
        return $this->hasMany(LanguageProficiency::class);
    }

    public function medicalRecords()
    {
        return $this->hasMany(MedicalRecord::class);
    }

    public function token()
    {
        return $this->belongsTo(Token::class);
    }

    public function lga()
    {
        return $this->belongsTo(Lga::class);
    }

    public function interview()
    {
        return $this->hasOne(Interview::class);
    }

    public function applicantImage()
    {
        return Storage::url($this->picture);
    }
}
