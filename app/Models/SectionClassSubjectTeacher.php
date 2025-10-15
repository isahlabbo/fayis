<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SectionClassSubjectTeacher extends BaseModel
{
    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function sectionClassSubject()
    {
        return $this->belongsTo(SectionClassSubject::class);
    }

    public function subjectTeacherTermlyUploads ()
    {
        return $this->hasMany(SubjectTeacherTermlyUpload::class);
    }
    public function getDownloadableName()
    {
        
        return strtolower(str_replace(' ','_',$this->sectionClassSubject->subject->name.'_of_'.
        $this->sectionClassSubject->sectionClass->name.'_for_'
        .$this->teacher->name));
    }
}
