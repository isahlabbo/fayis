<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use App\Models\SectionClass;

class SectionClassStudentExport implements FromView
{
    protected $sectionClass = null;

    public function __construct(SectionClass $sectionClass)
    {
        $this->sectionClass = $sectionClass;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
        return view('section.class.student.template',['sectionClass'=>$this->sectionClass]);
    }
}
