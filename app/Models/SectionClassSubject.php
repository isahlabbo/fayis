<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SectionClassSubject extends BaseModel
{
    public function sectionClassSubjectTeachers()
    {
        return $this->hasMany(SectionClassSubjectTeacher::class);
    }
    public function questions()
    {
        return $this->hasMany(Question::class);
    }
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
    public function sectionClassSubjectDownloads()
    {
        return $this->hasMany(SectionClassSubjectDownloads::class);
    }

    public function sectionClassSubjectUploads()
    {
        return $this->hasMany(SectionClassSubjectUploads::class);
    }

    public function examSubjectQuestionSections()
    {
        return $this->hasMany(ExamSubjectQuestionSection::class);
    }

    public function sectionClass()
    {
        return $this->belongsTo(SectionClass::class);
    }
    public function currentExam()
    {
        return $this->sectionClass->sectionClassTermlyExams->where('academic_session_term_id',$this->currentSessionTerm()->id)->first();
    }
    public function currentQuestionSections()
    {
        return $this->examSubjectQuestionSections->where('section_class_termly_exam_id', $this->currentExam()->id);
    }
    public function thisSessionTermResultUpload($session, $term)
    {
        foreach ($this->sectionClassSubjectTeachers as $classTeacher) {
            foreach($classTeacher->subjectTeacherTermlyUploads->where('term_id',$term->id) as $upload){
                if($upload->academicSessionTerm->academicSession->id == $session->id){
                    return $upload;
                }
            }
        }
    }
    
    public function availableResultUploads($sessionId=null, $termId=null)
    {
        $session = Section::find(1);
        if(!$sessionId){
            $sessionId = $session->currentSession()->id;
        }
        if(!$termId){
            $termId = $session->currentSessionTerm()->term->id;
        }
        $uploads = [];
        foreach ($this->sectionClassSubjectTeachers as $classTeacher) {
            foreach($classTeacher->subjectTeacherTermlyUploads->where('term_id',$termId) as $upload){
                if($upload->academicSessionTerm->academicSession->id == $sessionId){
                    $uploads[] = $upload;
                }
            }
        }
        return $uploads;
    }

    public function hasCurrentTermUpload()
    {
        $flag = false;
        foreach($this->activeSectionClassSubjectTeacher()->subjectTeacherTermlyUploads->where('academic_session_term_id',$this->currentSessionTerm()->id) as $upload){
           return $upload;
        }
        return $flag;
    }

    public function termlyUpload($termId)
    {
        $uploads = [];
        foreach($this->currentSession()->academicSessionTerms as $academicSessionTerm){
            if($academicSessionTerm->term->id == $termId){
                $sessionTerm = $academicSessionTerm;
            }
        }
        
            foreach($this->activeSectionClassSubjectTeacher()->subjectTeacherTermlyUploads
            ->where('academic_session_term_id',$sessionTerm->id) as $upload){
                $uploads[] = $upload;
            }
            return $uploads;
       
    }
    
    public function activeSectionClassSubjectTeacher()
    {
        $sectionClassSubjectTeacher = null;
        foreach ($this->sectionClassSubjectTeachers->where('status','Active') as $sectionClassSubjectTeacher) {
            return $sectionClassSubjectTeacher;
        }

        if(!$sectionClassSubjectTeacher){
            $sectionClassSubjectTeacher = $this->sectionClassSubjectTeachers()->create(['teacher_id'=>rand(1,10)]);
        }
        
        return $sectionClassSubjectTeacher;
    }
}
