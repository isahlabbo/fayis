<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuranKnowledge extends BaseModel
{
    public function recitationParticipations()
    {
        return $this->hasMany(RecitationParticipation::class);
    }
}
