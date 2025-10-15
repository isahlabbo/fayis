<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Str;
class AcademicSessionTerm extends BaseModel
{
    public function academicSession(Type $var = null)
    {
        return $this->belongsTo(AcademicSession::class);
    }

    public function term(Type $var = null)
    {
        return $this->belongsTo(Term::class);
    }

    public function sectionClassStudentTerms()
    {
        return $this->hasMany(SectionClassStudentTerm::class);
    }

    public function subjectTeacherTermlyUploads()
    {
        return $this->hasMany(SubjectTeacherTermlyUpload::class);
    }

    public function countDown()
    {
       
        $availableTime = strtotime($this->end_at) - time();
        $days = floor($availableTime/86400);
        if($days > 0){
            return $days.' '.Str::plural('Day',$days);
        }else{
            $hours = floor($availableTime/3600);
            return $hours.' '.Str::plural('Hour',$hours);
        }
    }

    

}
