<?php

namespace App\Models;

use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Student extends BaseModel
{
    public function sectionClassStudents()
    {
        return $this->hasMany(SectionClassStudent::class);
    }

    public function lga()
    {
        return $this->belongsTo(Lga::class);
    }

    public function studentType()
    {
        return $this->belongsTo(StudentType::class);
    }

    public function sectionStudentGraduations ()
    {
        return $this->hasMany(SectionStudentGraduation::class);
    }

    public function sectionClass()
    {
        return $this->belongsTo(SectionClass::class);
    }

    public function profileImage()
    {
        return Storage::url($this->picture);
    }

    public function activeSectionClass()
    {
        $sectionClass = null;
        foreach($this->sectionClassStudents->where('status', 'Active') as $sectionClassStudent){
            $sectionClass = $sectionClassStudent->sectionClass;
        }
        return $sectionClass;
    }

    public function currentStudentClass()
    {
        $sectionClass = null;
        foreach($this->sectionClassStudents->where('status', 'Active') as $sectionClassStudent){
            $sectionClass = $sectionClassStudent;
        }
        return $sectionClass;
    }

    public function guardian()
    {
        return $this->belongsTo(Guardian::class);
    }

    public function gender()
    {
        return $this->belongsTo(Gender::class);
    }

    public function assignToThisClass($sectionClassId,$status,AcademicSession $session)
    {
       
        $studentClass = $this->sectionClassStudents()->firstOrCreate([
            'status'=>$status,
            'section_class_id'=>$sectionClassId,
            'academic_session_id'=>$session->id
            ]);
        foreach($session->academicSessionTerms as $academicSessionTerm){
            $studentTerm = $studentClass->sectionClassStudentTerms()->create(['academic_session_term_id'=>$academicSessionTerm->id]);
        }
        $studentClass->updateActiveTerm();
    }

    public function currentSessionTerm()
    {
        $term = null;
        foreach($this->sectionClassStudents->where('status', 'Active') as $sectionClassStudent){
            foreach($sectionClassStudent->sectionClassStudentTerms->where('status','Active') as $sectionClassStudentTerm){
                $term = $sectionClassStudentTerm;
            }
        }
        return $term;
    }

    public function repeatThisClass(SectionClassStudent $sectionClassStudent,AcademicSession $session)
    {
        
        $this->sectionClassStudentRepeatings()->firstOrCreate([
            'academic_session_id'=>$session->id,
            'section_class_student_id'=>$sectionClassStudent->id,
            ]);

        $studentClass = $this->sectionClassStudents()->firstOrCreate([
            'status'=>'Active',
            'section_class_id'=>$sectionClassStudent->sectionClass->id,
            'academic_session_id'=>$session->id,
            ]);
        foreach($session->academicSessionTerms as $academicSessionTerm){
            $studentTerm = $studentClass->sectionClassStudentTerms()->create(['academic_session_term_id'=>$academicSessionTerm->id]);
            if($studentTerm->academicSessionTerm->term->id == 1){
                $studentTerm->update(['status'=>'Active']);
            }
        }
    }


}
