<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubjectTeacherTermlyUpload extends BaseModel
{
    public function sectionClassSubjectTeacher()
    {
        return $this->belongsTo(SectionClassSubjectTeacher::class);
    }

    public function term()
    {
        return $this->belongsTo(Term::class);
    }

    public function academicSessionTerm()
    {
        return $this->belongsTo(AcademicSessionTerm::class);
    }

    public function studentResults()
    {
        return $this->hasMany(StudentResult::class);
    }

    public function average()
    {
        $count = count($this->studentResults);
        $total = 0;
        foreach($this->studentResults as $result){
            $total += ($result->first_ca + $result->second_ca + $result->assignment + $result->exam);
        }
        if($count == 0){
            $count = 1;
        }
        return $total/$count;
    }

    public function getLevel() {
        $level = 0;
        foreach($this->studentResults as $result){
            if($result->first_ca !== null && $result->first_ca > 0){
                $level++;
            }
            if($result->second_ca !== null && $result->second_ca > 0){
                $level++;
            }
            if($result->exam !== null && $result->exam > 0){
                $level++;
            }
            if($result->assignment !== null && $result->assignment > 0){
                $level++;
            }
            if($level > 1){
                $this->level = $level;
                $this->save();
                break;
            }
        }
        return $this->level;
    }

    public function gradePercentage($grade)
    {
        $count = $this->gradeCount($grade);
        $total = $this->totalGradeCounts();

        if($total == 0){ 
            $total = 1;
        }  

        return number_format(($count/$total)*100,2);
    }

    function totalGradeCounts() {
        $count = 0;
        foreach(GradeScale::cursor() as $gradeScale){
            $count += $this->gradeCount($gradeScale->grade);
        }
        return $count;
    }

    public function expectedScoresOfAllStudents()
    {
        return count($this->studentResults) * 100;
    }

    public function actualScoresOfAllStudents()
    {
        return $this->average * count($this->studentResults);
    }

    public function diviatedScoresOfAllStudents()
    {
        return $this->expectedScoresOfAllStudents() - $this->actualScoresOfAllStudents();
    }

    public function percentageScoresOfAllStudents()
    {
        return (100 * $this->actualScoresOfAllStudents())/ $this->expectedScoresOfAllStudents();
    }

    public function computeAndSaveUploadAverage()
    {
        $scores = 0;
        $students = 0;
        foreach($this->studentResults as $result){
            $scores += $result->total;
            $students++;
        }
        $this->average = $scores/$students;
        $this->save();
    }

    public function gradeCount($grade)
    {
        $count = 0;
        foreach($this->studentResults->where('grade',$grade) as $studentResult){
            if($studentResult->sectionClassStudentTerm && $studentResult->sectionClassStudentTerm->academicSessionTerm->id == $this->currentSessionTerm()->id){
                $count++;
            }
        }
        return $count;
    }

    public function position($total)
    {
        $scoreBoard = [];
        
        foreach ($this->studentResults as $studentResult) {
            $scoreBoard[] = $studentResult->total;
        }
        
        // remove the duplicate score from the array
        array_unique($scoreBoard);
       
        // sort array decending order
        rsort($scoreBoard);
        foreach($scoreBoard as $key => $value){
            if($total == $value){
                return ($key+1);
            }
        }
    }
}
