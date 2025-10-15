<?php

namespace App\Exports;

use App\Models\Psychomotor;
use App\Models\AffectiveTrait;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class StudentAccessmentExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */

    public $sectionClass = null;

    public function __construct($sectionClass)
    {
        $this->sectionClass = $sectionClass;
    }
    
    public function view(): View
    {
        return view('section.class.result.accessment', ['psychomotors'=>Psychomotor::all(),'affectiveTraits'=>AffectiveTrait::all(),'sectionClass' => $this->sectionClass]);
    }
    
}
