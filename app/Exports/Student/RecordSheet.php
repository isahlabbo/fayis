<?php

namespace App\Exports\Student;

use Maatwebsite\Excel\Concerns\FromView;

class RecordSheet implements FromView
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
        return view('school.teacher.scoreSheet.record', ['students' => $this->students
        ]);
    }
}
