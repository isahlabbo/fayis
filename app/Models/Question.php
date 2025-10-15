<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends BaseModel
{
    public function questionType()
    {
        return $this->belongsTo(QuestionType::class);
    }

    public function examSubjectQuestionSection()
    {
        return $this->belongsTo(ExamSubjectQuestionSection::class);
    }

    public function sectionClassSubject(Type $var = null)
    {
        return $this->belongsTo(SectionClassSubject::class);
    }

    public function availableOptions()
    {
        $available = [];
        $remaining = [];
        foreach ($this->options as $option) {
            $available[] = $option->name;
        }
        foreach(['A','B','C','D','E'] as $name){
            if(!in_array($name, $available)){
                $remaining[] = $name;
            }
        }
        return $remaining;

    }

    public function options()
    {
        return $this->hasMany(Option::class);
    }
    
    public function questionItems()
    {
        return $this->hasMany(QuestionItem::class);
    }
}
