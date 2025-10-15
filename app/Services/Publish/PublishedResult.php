<?php
namespace App\Services\Publish;
use App\Models\RemarkScale;

trait PublishedResult
{
   
    public function publishedPosition()
    {
        if($this->academicSessionTerm->term->id == 3){
            $allStudentsScoreInTheClass = [];
            foreach($this->sectionClassStudent->sectionClass->sectionClassStudents->where('academic_session_id',$this->academicSessionTerm->academicSession->id) as $sectionClassStudent){
                $allStudentsScoreInTheClass[] = $sectionClassStudent->pulishedResultAverage();
            }
            if($this->sectionClassStudent->sectionClass->resultType->id == 1){
                // remove the duplicate score from the array
                array_unique($allStudentsScoreInTheClass);
                // sort array decending order
                rsort($allStudentsScoreInTheClass);
                foreach($allStudentsScoreInTheClass as $key => $value){
                    if($this->sectionClassStudent->pulishedResultAverage() == $value){
                        return $this->getValidPositionName(($key+1));
                    }
                }
            }else{
                $score = $this->studentTermTotalScore();
                $totalMarks = count($this->sectionClassStudent->sectionClass->sectionClassSubjects)*100;
                $percentage = 100 * ($score/$totalMarks);
                foreach(RemarkScale::all() as $scale){
                    if($percentage >= $scale->percent){
                        return $scale->remark;
                    }
                }
            }    
        }else{
            $posiotion = $this->sectionClassStudentTermResultPublish->position;
        }
    }

    

    public function publishedClassAverage()
    {
        if($this->academicSessionTerm->term->id == 3){
            $denominator = 0;
            $classScore = 0;
            foreach($this->sectionclassStudent->sectionClassStudentTerms as $sectionClassStudentTerm){
                if($sectionClassStudentTerm->sectionClassStudentTermResultPublish && $sectionClassStudentTerm->sectionClassStudentTermResultPublish->class_average){
                    $classScore += $sectionClassStudentTerm->sectionClassStudentTermResultPublish->class_average;
                    $denominator+=1;
                }
            }
            if($denominator == 0){
                $denominator+=1;
            }
            return number_format($classScore/$denominator,2);
        }else{
            $obtainMarks = number_format($this->sectionClassStudentTermResultPublish->class_average,2);
        }
    }

    public function publishedStudentAverage()
    {
        if($this->academicSessionTerm->term->id == 3){
            $denominator = 0;
            $classScore = 0;
            foreach($this->sectionclassStudent->sectionClassStudentTerms as $sectionClassStudentTerm){
                if($sectionClassStudentTerm->sectionClassStudentTermResultPublish && $sectionClassStudentTerm->sectionClassStudentTermResultPublish->student_average){
                    $classScore += $sectionClassStudentTerm->sectionClassStudentTermResultPublish->student_average;
                    $denominator+=1;
                }
            }
            if($denominator == 0){
                $denominator+=1;
            }
            return number_format($classScore/$denominator,2);
        }else{
            $obtainMarks = number_format($this->sectionClassStudentTermResultPublish->class_average,2);
        }
    }

    public function publishedTotalMarks()
    {
        if($this->academicSessionTerm->term->id == 3){
            $denominator = 0;
            $classScore = 0;
            foreach($this->sectionclassStudent->sectionClassStudentTerms as $sectionClassStudentTerm){
                if($sectionClassStudentTerm->sectionClassStudentTermResultPublish && $sectionClassStudentTerm->sectionClassStudentTermResultPublish->total_marks){
                    $denominator++;
                    $classScore += $sectionClassStudentTerm->sectionClassStudentTermResultPublish->total_marks;
                }
            }
            if($denominator == 0){
                $denominator+=1;
            }
            return number_format($classScore/$denominator,2);
        }else{
            $obtainMarks = number_format($this->sectionClassStudentTermResultPublish->total_marks,2);
        }
    }
    public function publishedObtainedMarks()
    {
        if($this->academicSessionTerm->term->id == 3){
            $denominator = 0;
            $classScore = 0;
            foreach($this->sectionclassStudent->sectionClassStudentTerms as $sectionClassStudentTerm){
                if($sectionClassStudentTerm->sectionClassStudentTermResultPublish && $sectionClassStudentTerm->sectionClassStudentTermResultPublish->obtain_marks){
                    $denominator+=1;
                    $classScore += $sectionClassStudentTerm->sectionClassStudentTermResultPublish->obtain_marks;
                }
            }
            if($denominator == 0){
                $denominator+=1;
            }
            return number_format($classScore/$denominator,2);
        }else{
            $obtainMarks = number_format($this->sectionClassStudentTermResultPublish->obtain_marks,2);
        }
    }

}
