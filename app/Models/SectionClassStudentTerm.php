<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Services\Publish\PublishAble;
use App\Services\Publish\PublishedResult;

class SectionClassStudentTerm extends BaseModel
{
    use PublishAble, PublishedResult;

    public function sectionClassStudentTermResultPublish()
    {
        return $this->hasOne(SectionClassStudentTermResultPublish::class);
    }
    public function studentResults()
    {
        return $this->hasMany(StudentResult::class);
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }

    

    public function totalPayableAmount()
    {
        return $this->sectionClassStudent->sectionClass->totalFee($this->academicSessionTerm->term, $this->sectionClassStudent->student);
    }

    public function payableDiscount()
    {
        return $this->sectionClassStudent->studentDiscount($this->academicSessionTerm->term);
    }

    public function actualPayableAmount()
    {
        return $this->totalPayableAmount() - $this->payableDiscount();
    }

    public function generateInvoice()
    {
        $amount = $this->actualPayableAmount();

        $title = 'School Fee';
        $invoice = $this->invoice()->firstOrCreate([
            'academic_session_id'=> $this->currentSession()->id,
            'title'=>$title,
            ]);
        $invoice->update(['amount'=>$amount,'number'=>str_replace('/','-',$this->academicSessionTerm->academicSession->name).'/'.sprintf('%04d',count($this->academicSessionTerm->academicSession->invoices))]);
        
        return $invoice;    
    }

    public function studentTermTotalScore()
    {
        $total = 0;
        foreach($this->studentResults as $studentResult){
            $total = $total+$studentResult->total;
        }
        return $total;
    }
    
    public function sectionClassStudent()
    {
        return $this->belongsTo(SectionClassStudent::class);
    }

    public function academicSessionTerm()
    {
        return $this->belongsTo(AcademicSessionTerm::class);
    }

    public function sectionClassStudentTermAccessment()
    {
        return $this->hasOne(SectionClassStudentTermAccessment::class);
    }

    public function studentAverage()
    {
        $total = 0;
        $count = 0;
        foreach($this->studentResults as $result){
            $count++;
            $total = $total + $result->total;
        }
        if($count == 0){
            $count = 1;
        }
        return number_format($total/$count,2);
    }
    
    public function publishThisTermResult()
    {
        $publish = $this->sectionClassStudentTermResultPublish()->firstOrCreate([]);
        $publish->updatePublishRecord();
    }

   
}
