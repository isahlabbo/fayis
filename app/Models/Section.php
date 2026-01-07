<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends BaseModel
{
    
    public function sectionClasses()
    {
        return $this->hasMany(SectionClass::class);
    }

    public function psychomotors()
    {
        return $this->hasMany(Psychomotor::class);
    }

    public function affectiveTraits()
    {
        return $this->hasMany(AffectiveTrait::class);
    }

    public function studentCounts(){
        $studentCounts = 0;
        foreach($this->sectionClasses as $sectionClass){
            $studentCounts += $sectionClass->studentCounts();
        }
        return $studentCounts;
    }

    public function submitted(){
        $submitted = 0;
        foreach($this->sectionClasses as $sectionClass){
            $submitted += $sectionClass->submitted();
        }
        return $submitted;
    }

    public function subjects(){
        $subjects = 0;
        foreach($this->sectionClasses as $sectionClass){
            $subjects += $sectionClass->subjects();
        }
        return $subjects;
    }

    public function inProgress(){
        $inProgress = 0;
        foreach($this->sectionClasses as $sectionClass){
            $inProgress += $sectionClass->inProgress();
        }
        return $inProgress;
    }

    public function notAttempted(){
        $notAttempted = 0;
        foreach($this->sectionClasses as $sectionClass){
            $notAttempted += $sectionClass->notAttempted();
        }
        return $notAttempted;
    }

    public function submittedToClassMaster(){
        $submittedToClassMaster = 0;
        foreach($this->sectionClasses as $sectionClass){
            $submittedToClassMaster += $sectionClass->submittedToClassMaster();
        }
        return $submittedToClassMaster;
    }

    public function submittedToExamOffice(){
        $submittedToExamOffice = 0;
        foreach($this->sectionClasses as $sectionClass){
            $submittedToExamOffice += $sectionClass->submittedToExamOffice();
        }
        return $submittedToExamOffice;
    }

    public function published(){
        $published = 0;
        foreach($this->sectionClasses as $sectionClass){
            $published += $sectionClass->published();
        }
        return $published;
    }
    
    public function teacherAllocations($teacherId)
    {
        $allocations = [];
        foreach($this->sectionClasses as $sectionClass){
            foreach($sectionClass->sectionClassSubjects->where('status','Active') as $sectionClassSubject){
                foreach($sectionClassSubject->sectionClassSubjectTeachers->where('teacher_id',$teacherId)->where('status','Active') as $assignment){
                    $allocations[] = $assignment;
                }
            }
        }
        return $allocations;
    }

    public function allTeachersAllocationInSection()
    {
        $allocations = [];
        foreach($this->sectionClasses as $sectionClass){
            $allocations = array_merge($allocations, $sectionClass->allTeachersAllocationInSection());
        }
        return $allocations;
    }

    public function sectionStudentGraduations ()
    {
        return $this->hasMany(SectionStudentGraduation::class);
    }

    public function nextSection()
    {
        
        return Section::where('level',$this->level+1)->first();
    }

    

    public function students()
    {
        $students = [];
        
        foreach($this->sectionClasses as $sectionClass){
           foreach($sectionClass->sectionClassStudents as $student){
               $students[] = $student;
           }
        }
        return $students;
    }
    public function sectionReports()
    {
        $report = 0;
        foreach($this->sectionClasses as $sectionClass){
            $report += count($sectionClass->classReports());
        }
        return $report;
    }
    public function subjectResultUploads()
    {
        $uploadedResult = [];
        $awaitingResult = [];
        
        foreach ($this->sectionClasses as $sectionClass) {
            $uploadedResult = array_merge($sectionClass->subjectResultUploads()['uploaded'],$uploadedResult);
            $awaitingResult = array_merge($sectionClass->subjectResultUploads()['awaiting'],$awaitingResult);
        }

        return ['uploaded' => $uploadedResult, 'awaiting' => $awaitingResult];
    }

    public function yearSequences()
    {
        $sequences = [];
        for ($sequence=1; $sequence <= $this->duration ; $sequence++) { 
            switch ($sequence) {
                case '1':
                    $sequences[] = 'First';
                    break;
                case '2':
                    $sequences[] = 'Second';
                    break;
                case '3':
                    $sequences[] = 'Third';
                    break;
                case '4':
                    $sequences[] = 'Forth';
                    break;
                case '5':
                    $sequences[] = 'Fifth';
                    break;
                case '6':
                    $sequences[] = 'Sixth';
                    break;
                default:
                    break;
            }
        }
        return $sequences;
    }

    public function getYearSequence()
    {
        $sequence = null;

        foreach($this->sectionClasses as $sectionClass){
            $sequence = $sectionClass->year_sequence;
        }
        if($sequence){
            switch ($sequence) {
                case 'First':
                    $sequence = 'Second';
                    break;
                case 'Second':
                    $sequence = 'Third';
                    break;
                case 'Third':
                    $sequence = 'Forth';
                    break;
                case 'Forth':
                    $sequence = 'Fifth';
                    break;
                case 'Fifth':
                    $sequence = 'Sixth';
                    break;
                
                default:
                    $sequence = 'Seventh';
                    break;
            }
        }else{
            $sequence = 'First';
        }
        return $sequence;
    }




    // section class generations methods

    public function requiredClasses()
    {
        $classes = [];
        $name = $this->name;
        if(strpos($this->name,' ')){
            $name = substr($this->name,0, strpos($this->name,' '));
        }
        for ($level=1; $level <= $this->duration ; $level++) {
            foreach(SectionClassGroup::all() as $classGroup){
                $classes[] = ['section_class_group_id'=>$classGroup->id,'name'=>$name.' '.$level.' '.$classGroup->name,'sequence'=>$this->levelYearSequence($level)];
            }
        }
        return $classes;
    }

    public function levelYearSequence($level)
    {
        switch ($level) {
            case '1':
                $sequence = 'First';
                break;
            case '2':
                $sequence = 'Second';
                break;
            case '3':
                $sequence = 'Third';
                break;
            case '4':
                $sequence = 'Forth';
                break;
            case '5':
                $sequence = 'Fifth';
                break;
            
            default:
                $sequence = 'Sixth';
                break;
        }
        return $sequence;
    }
}
