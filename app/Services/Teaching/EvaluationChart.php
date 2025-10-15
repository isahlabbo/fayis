<?php
namespace App\Services\Teaching;
use App\Models\Teaching\EvaluationChart as chart;

trait EvaluationChart
{
    use chart;

    public function label()
    {
        $labels = [];
        foreach($this->suctionClassSubjects as $sectionClassSubject){
            $labels[] = $sectionClassSubject->subject->name;
        }
        return $labels;
    }

    public function dataSet($type, $session)
    {
        $data = [];
        foreach($this->sectionClassSubjects as $sectionClassSubject){
            $data[] = $sectionClassSubject->thisSessionTermResultUpload($session,$term)->percentageScoresOfAllStudents();
        }
        return $data;
    }

    public function makeChart($session, $term)
    {
        $chart = new chart($session, $term)
    }
}