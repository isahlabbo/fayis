<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionItem extends BaseModel
{
    public function question(Type $var = null)
    {
        return $this->belongsTo(Question::class);
    }
}
