<?php

namespace App\Exports\Student;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ScoreSheet implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public $students = [];

    public function __construct($students)
    {
        $this->students = $students;
    }
    
    public function view(): View
    {
        return view('school.teacher.scoreSheet.sheet', ['students' => $this->students
        ]);
    }
}

