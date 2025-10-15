<?php
namespace App\Services\Diagnose;

use Illuminate\Support\Facades\Storage;



trait ClassMode
{
    public $reports = [];

    public function studentSameAdmissionNumbers()
    {
        $checklist = [];
        $duplicate = [];
        foreach($this->sectionClassStudents->where('status','Active') as $sectionClassStudent){
            if(in_array($sectionClassStudent->student->admission_no, $checklist)){
                $duplicate[] = $sectionClassStudent->student->admission_no;
            }else{
                $checklist[] = $sectionClassStudent->student->admission_no;
            }
        }
        if(!empty($duplicate)){
            $this->reports[] = ['status'=>1,'message'=>'There are some students with same admission number we recomend to regenerate all class student admission no'];
        }
    }

    public function classRepeatingStudents()
    {
        $repeatingStudents = $this->sectionClassStudents->where('status','Repeat');
        if(count($repeatingStudents) > 0){
            $this->reports[] = ['status'=>2,'message'=>$this->name.' Has '.count($repeatingStudents).' Repeating student but this can modirated through adding some mark to the student aggregate'];
        }
    }

    public function studentsWithFewOrNoTerm()
    {
        $flag = false;
        foreach ($this->sectionClassStudents->where('status','Active') as $sectionClassStudent) {
            if(count($sectionClassStudent->sectionClassStudentTerms) < 3){
                $flag = true;
            }
        }
        if($flag){
            $this->reports[] = ['status'=>3,'message'=>$this->name.' Has some students with termly record issues'];
        }
    }

    public function studentsWithNoCurrentTerm()
    {
        $flag = false;
        foreach ($this->sectionClassStudents->where('status','Active') as $sectionClassStudent) {
            if(!$sectionClassStudent->currentStudentTerm()){
                $flag = true;
            }
        }
        if($flag){
            $this->reports[] = ['status' => 4, 'message'=>$this->name.' Has some students that was not belongs to the current term'];
        }
    }

    public function resultAreMoreThanStudentInClass()
    {
        $flag = false;
        foreach ($this->sectionClassSubjects as $classSubject) {
            foreach($classSubject->sectionClassSubjectTeachers->where('status','Active') as $subjectTeacher){
                foreach($subjectTeacher->subjectTeacherTermlyUploads->where('academic_session_term_id',$this->currentSessionTerm()->id) as $upload){
                    if(count($upload->studentResults) > count($upload->sectionClassSubjectTeacher->sectionClassSubject->sectionClass->activeStudentIds())){
                        $flag = true;
                    }
                }
            }
            
        }
        if($flag){
            $this->reports[] = ['status' => 7, 'message'=>' Has some some duplicate uploade'];
        }
    }

    public function studentWithDuplicateActiveClass()
    {
        $flag = false;
        foreach ($this->sectionClassStudents->where('status','Active') as $sectionClassStudent) {
            if(count($sectionClassStudent->student->sectionClassStudents->where('status','Active'))> 1){
                $flag = true;
            }
        }
        if($flag){
            $this->reports[] = ['status'=>5, 'message'=>$this->name.' Has some students that are actively in another class'];
        }
    }

    public function studentAreNotInTheSameTermWithSession()
    {
        $flag = false;
        foreach ($this->sectionClassStudents->where('status','Active') as $sectionClassStudent) {
            if($sectionClassStudent->sectionClassStudentTerms->where('status','Active')->first()
            ->academicSessionTerm->term->id != $this->currentSessionTerm()->term->id){
                $flag = true;
            }
        }
        if($flag){
            $this->reports[] = ['status'=>6, 'message'=>$this->name.' Has some students term differently than session term'];
        }
    }

    public function classReports()
    {
        if(count($this->sectionClassStudents) > 0){
            $this->studentSameAdmissionNumbers();
            $this->classRepeatingStudents();
            $this->studentsWithFewOrNoTerm();
            $this->studentsWithNoCurrentTerm();
            $this->studentWithDuplicateActiveClass();
            $this->studentAreNotInTheSameTermWithSession();
            $this->resultAreMoreThanStudentInClass();
        }
        return $this->reports;
    }
}