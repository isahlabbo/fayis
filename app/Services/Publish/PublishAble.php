<?php
namespace App\Services\Publish;

use App\Models\RemarkScale;

trait PublishAble
{
   
    public function position()
    {
        $allStudentsScoreInTheClass = [];
        foreach($this->sectionClassStudent->sectionClass->sectionClassStudents->where('status','Active') as $sectionClassStudent){
            foreach($sectionClassStudent->sectionClassStudentTerms as $sectionClassStudentTerm){
                if(count($sectionClassStudentTerm->studentResults) > 0 && $sectionClassStudentTerm->academicSessionTerm->term->id == $this->academicSessionTerm->term->id){
                    $allStudentsScoreInTheClass[] = $sectionClassStudentTerm->studentTermTotalScore();
                }
            }
        }
        if($this->sectionClassStudent->sectionClass->resultType->id == 1){
            // remove the duplicate score from the array
            array_unique($allStudentsScoreInTheClass);

            // sort array decending order
            rsort($allStudentsScoreInTheClass);
            foreach($allStudentsScoreInTheClass as $key => $value){
                if($this->studentTermTotalScore() == $value){
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
        
    }

    public function obtainMarks()
    {
        return $this->studentTermTotalScore();
    }

    public function totalMarks()
    {
        return count($this->sectionClassStudent->sectionClass->sectionClassSubjects) * 100;
    }

    public function classAverage()
    {
        return $this->sectionClassStudent->sectionClass->classAverage($this->academicSessionTerm->term);
    }

    public function studentAverage()
    {
        return $this->studentAverage();
    }

    public function getValidPositionName($position)
    {
        switch ($position) {
            case '1':
                $position = $position.'ST';
                break;
            case '2':
                $position = $position.'ND';
                break;
            case '3':
                $position = $position.'RD';
                break;
            case '21':
                $position = $position.'ST';
                break;
            case '22':
                $position = $position.'ND';
                break;
            case '23':
                $position = $position.'RD';
                break;
            case '31':
                $position = $position.'ST';
                break;
            case '32':
                $position = $position.'ND';
                break;
            case '33':
                $position = $position.'RD';
                break;  
            case '41':
                $position = $position.'ST';
                break;
            case '42':
                $position = $position.'ND';
                break;
            case '43':
                $position = $position.'RD';
                break;           
            default:
                $position = $position.'TH';
                break;
        }
        return $position;
    }

    
}
