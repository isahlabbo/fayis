<?php

namespace App\Exports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class DataExport implements FromView
{
     /**
    * @return \Illuminate\Support\Collection
    */
   

    public function __construct()
    {
        
    }
    
    public function view(): View
    {
        return view('school.data');
    }
}
