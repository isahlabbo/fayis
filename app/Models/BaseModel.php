<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class BaseModel extends Model
{
    protected $guarded = [];

    public function currentSession()
    {
        return AcademicSession::where('status','Active')->first();
    }

    public function generateQRCode($data, $size = 150)
    {
        return QrCode::size($size)->generate($data);
    }

    public function currentSessionTerm()
    {
        return $this->currentSession()->academicSessionTerms->where('status','Active')->first();
    }

    public function nextSession()
    {
        $session = AcademicSession::find($this->currentSession()->id + 1);
        if(!$session){
            $oldSessionName = $this->currentSession()->name;
            $start = substr($oldSessionName,0,4)+1;
            $end = substr($oldSessionName,5,4)+1;
            $session = AcademicSession::firstOrCreate(['name'=>$start.'/'.$end]);
            foreach([1,2,3] as $termId){
                $session->academicSessioTerms()->firstOrCreate(['term_id'=>$termId]);
            }
        }
        return $session;
    }

    public function admissionLetter()
    {
        return AdmissionLetter::firstOrCreate([]);
    }

}
