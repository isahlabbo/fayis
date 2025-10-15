<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends BaseModel
{
    public function transaction()
    {
        return $this->hasOne(Transaction::class);
    }

    public function userInvoice()
    {
        return $this->hasOne(UserInvoice::class);
    }

    public function sectionClassStudentTerm()
    {
        return $this->belongsTo(SectionClassStudentTerm::class);
    }

    public function academicSession()
    {
        return $this->belongsTo(AcademicSession::class);
    }

    public function charges()
    {
        return $this->guardianComissionCharges();
    }

    public function payableAmount()
    {
        return $this->guardianComissionCharges() + $this->amount;
    }

    public function guardianComissionCharges()
    {
        return ($this->amount/100) * 0.6;
    }

    public function schoolComissionCharges()
    {
        return ($this->amount/100) * 1.4;
    }


    public function schoolCharges()
    {
        return number_format(99.999 * ($this->totalFee() - $this->getwayCharges()) /100, 2);
    }

    public function getwayCharges()
    {
        return 2 * ($this->totalFee()/100);
    }

    public function otherCharges()
    {
        // i have added 100 naira for atleast something to go to marchant
        
        $shareableAmount = 50 + ($this->guardianComissionCharges() + $this->schoolComissionCharges()) - $this->getwayCharges();
       
        return $shareableAmount;
    }

    public function verifyPayment()
    {
        if($this->status == 'paid' && !$this->transaction){
            // get transaction details
            $card = Card::find(1);
            $card->transactions()->create(['invoice_id'=>$this->id,'transaction_id'=>$this->getValidTransaction()['transaction_id']]);
        }
    }

    public function getValidTransaction()
    {
    
        for($i=1; $i<=10; $i++){
            $transaction = Transaction::all()->last();
            if($transaction){
                $numberInId = strlen($transaction->transaction_id);
                $subId = substr($transaction->transaction_id,0,$numberInId-4);
                
                $ext = rand(1111,9999);
                
                $newTransactionId = $subId.$ext;
                
                if(!Transaction::where('transaction_id', $newTransactionId)->first()){
                    return ['transaction_id'=>$newTransactionId];
                }
            }
        }
    }

    

    public function totalFee()
    {
        return $this->charges() + $this->amount;
    }

    public function number()
    {
        return str_replace('/','-',$this->academicSession->name).'/'.$this->formatThisNumber(count($this->academicSession->invoices));
    }

    public function formatThisNumber($number)
    {
        if($number < 10){
            $number = '000'.$number;
        }elseif($number <100){
            $number = '00'.$number;
        }elseif($number < 1000){
            $number = '0'.$number;
        }

        return $number;
    }
}
