<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class StatisticsExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */

    public function __construct($academicSessionId, $termId)
    {
        $this->academicSessionId = $academicSessionId;
        $this->termId = $termId;
    }

    public function view(): View
    {
        $academicSession = \App\Models\AcademicSession::find($this->academicSessionId);
        $academicSessionTerm = $academicSession->academicSessionTerms->where('term_id', $this->termId)->first();
        return view('school.statistics', [
            'academicSessionTerm'=>$academicSessionTerm,
        ]);
    }
}
