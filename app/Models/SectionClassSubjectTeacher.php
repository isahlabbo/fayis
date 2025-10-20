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

    function getThisSessionUploads() {
        $uploads = $this->subjectTeacherTermlyUploads()->where('academic_session_id', $this->currentSession()->id)->get();
        // check if the uploads are 3 return the else create default uploads
        if(count($uploads) < 3) {
            $terms = Term::all();
            foreach($terms as $term) {
                $existingUpload = $this->subjectTeacherTermlyUploads()->where('term_id', $term->id)->where('academic_session_id', $this->currentSession()->id)->first();
                if(!$existingUpload) {
                    $newUpload = new SubjectTeacherTermlyUpload();
                    $newUpload->section_class_subject_teacher_id = $this->id;
                    $newUpload->term_id = $term->id;
                    $newUpload->academic_session_id = $this->currentSession()->id;
                    $newUpload->average = 0;
                    $newUpload->save();
                }
            }
            // reload uploads
            $uploads = $this->subjectTeacherTermlyUploads()->where('academic_session_id', $this->currentSession()->id)->get();
        }

        return $uploads;
    }
}
