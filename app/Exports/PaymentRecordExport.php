<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use App\Models\AcademicSession;

class PaymentRecordExport implements FromView
{
     /**
    * @return \Illuminate\Support\Collection
    */

    public $session = null;

    public function __construct($session)
    {
        $this->session = $session;
    }
    
    public function view(): View
    {
        return view('epayment.schoolfee.export', ['session'=>$this->session]);
    }
}
