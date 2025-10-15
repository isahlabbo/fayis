<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SectionClassStudentTermResultPublish extends BaseModel
{
    public function sectionClassStudentTerm()
    {
        return $this->belongsTo(SectionClassStudentTerm::class);
    }

    public function updatePublishRecord()
    {
        $this->update([
            'position' => $this->sectionClassStudentTerm->position(),
            'total_marks' => $this->sectionClassStudentTerm->totalMarks(),
            'obtain_marks' => $this->sectionClassStudentTerm->obtainMarks(),
            'class_average' => $this->sectionClassStudentTerm->classAverage(),
            'student_average' => $this->sectionClassStudentTerm->studentAverage(),
        ]);
        
    }
}
