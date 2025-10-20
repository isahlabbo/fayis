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

    public function gradePercentage($grade)
    {
        $count = count($this->studentResults);
        if($count == 0){
            $count =1;
        }
        return number_format(100 * ($this->gradeCount($grade)/$count),2);
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
        return count($this->studentResults->where('grade',$grade));
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
