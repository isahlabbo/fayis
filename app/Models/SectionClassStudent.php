<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SectionClassStudent extends BaseModel
{
    
    public function sectionClass()
    {
        return $this->belongsTo(SectionClass::class);
    }

    public function sectionClassStudentTerms ()
    {
        return $this->hasMany(SectionClassStudentTerm::class);
    }

    public function sectionClassStudentRepeatings ()
    {
        return $this->hasMany(SectionClassStudentRepeating::class);
    }

    public function sectionClassStudentPayments ()
    {
        return $this->hasMany(SectionClassStudentPayment::class);
    }

    public function payments ()
    {
        return $this->hasMany(Payment::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function ensureHasAllAcademicSessionTerm($uploadId) {
        $studentCurrentTermResults = [];
        
        $academicSessionTerms = $this->currentSession()->academicSessionTerms;
        
        foreach($academicSessionTerms as $academicSessionTerm){
            $existingStudentTerm = $this->sectionClassStudentTerms
            ->where('academic_session_term_id',$academicSessionTerm->id)
            ->first();
            
            if(!$existingStudentTerm){
                $existingStudentTerm = $this->sectionClassStudentTerms()->firstOrCreate([
                    'academic_session_term_id'=>$academicSessionTerm->id,
                    'subject_teacher_termly_upload_id'=>$uploadId,
                ]);
            }

            $studentCurrentTermResult = $existingStudentTerm->studentResults()->firstOrCreate([
                'subject_teacher_termly_upload_id'=>$uploadId,
            ]);

            if($this->currentSessionTerm()->id == $academicSessionTerm->id){
                $studentCurrentTermResults[] = $studentCurrentTermResult;
            }
            
        }
        return $studentCurrentTermResults;
    }

    public function currentStudentTerm()
    {
        
        if($term = $this->sectionClassStudentTerms->where('status','Active')->first()){
           return $term;
        }else{
            $this->updateActiveTerm();
            return $this->sectionClassStudentTerms->where('status','Active')->first();
        }
    }

    public function expectedScore()
    {
        return count($this->sectionclass->sectionClassSubjects) * 100;
    }

    public function studentDiscount($term)
    {
        $discount = 0;
        

            if($this->student->student_type_id == 3){
                // get staff children discount
                $discount += $this->sectionClass->section->staffChildrenDiscounts->where('term_id',$term->id)->first()->amount;
            }else{
                // check if the student is from neighboring state and get the discount of the state
                $discount += $this->student->lga->state->discount($term, $this->sectionClass->section);  
            }
       
        return $discount;
    }
    
    public function pulishedResultAverage()
    {
        $denoMinator = 0;
        $totalMarks = 0;
        foreach($this->sectionClassStudentTerms as $sectionClassStudentTerm){
            if($sectionClassStudentTerm->sectionClassStudentTermResultPublish && $sectionClassStudentTerm->sectionClassStudentTermResultPublish->obtain_marks){
                $denoMinator ++;
                $totalMarks += $sectionClassStudentTerm->sectionClassStudentTermResultPublish->obtain_marks;
            }
        }
        if($denoMinator == 0){
            $denoMinator += 1;
        }
        return $totalMarks/$denoMinator;
    }

    public function averageScore()
    {
        $total = 0;
        $count = 0;
        foreach ($this->sectionClassStudentTerms as $sectionClassStudentTerm) {
            if(count($sectionClassStudentTerm->studentResults)>0){
                $total += $sectionClassStudentTerm->studentTermTotalScore();
                $count ++;
            }
        }
        if($count == 0){
            $count = 1;
        }
        return $total/$count;
    }

    public function promoteToNextClass()
    {
        // dd($this->averageScore());
        foreach($this->sectionClassStudentTerms as $sectionClassStudentTerm){
            $sectionClassStudentTerm->update(['status'=>'Not Active']);
        }
        $this->update(['status'=>'Not Active']);

        if(100 * ($this->averageScore()/$this->expectedScore()) >= $this->sectionClass->pass_mark){
            // assign the student to the next class 
            if($this->sectionClass->hasNextClass()){
                $this->student->assignToThisClass($this->sectionClass->nextClass()->id,'Active',$this->nextSession());
            }else{
                //add to sectiongraduation list
                $this->student->sectionStudentGraduations([
                    'section_id'=>$this->sectionClass->section->id,
                    'academic_session_id'=>$this->currentSession()->id,
                ]);
            }
        }else{
            // repeat the class
            $this->student->repeatThisClass($this, $this->nextSession());
        }
    }

    public function paidAmount($term)
    {
        $amount = 0;
        foreach ($this->SectionClassStudentPayments->where('term_id',$term->id) as $payment) {
            $amount = $amount + $payment->amount;
        }
        return $amount;
    }

    public function feeAmount($term)
    {
        $amount = 0;
        foreach ($this->SectionClass->sectionClassPayments->where('term_id',$term->id) as $payment) {
            if($payment->gender_id == $this->student->gender_id || $payment->gender_id == 3){
                $amount = $amount + $payment->amount;
            }
        }
        return $amount;
    }

    public function modeOfPayment($term)
    {
        $mode = 'Cash';
        foreach($this->sectionClassStudentPayments->where('term_id',$term->id) as $payment){
            if($payment->mode){
                $mode = $payment->mode;
            }
        }
        return $mode;
    }

    public function updateActiveTerm()
    {
        
        foreach($this->sectionClassStudentTerms as $sectionClassStudentTerm){
            
            if($sectionClassStudentTerm->academicSessionTerm->term->id == $this->currentSessionTerm()->term->id){
                $sectionClassStudentTerm->update(['status'=>'Active']);
            }else{
                $sectionClassStudentTerm->update(['status'=>'No Active']);
            }
        }
       
    }

    public function nextSectionClassStudentTerm()
    {
        $term = null;
        foreach($this->sectionClassStudentTerms as $sectionClassStudentTerm){
            if($sectionClassStudentTerm->academicSessionTerm->term_id == $this->currentSessionTerm()->term_id + 1){
                $term = $sectionClassStudentTerm->academicSessionTerm;
            }
        }
        if(!$term){
            $session = AcademicSession::find($this->currentSession()->id+1);
            if($session){
                $term = $session->academicSessionTerms->first();
            }else{
                // create next session
            }
            
        }
        return $term;
    }
    public function uploadedResult()
    {
        $results = [];
        foreach ($this->sectionClassStudentTerms as $sectionClassStudentTerm) {
            foreach($sectionClassStudentTerm->studentResults as $result){
                $results[] = $result;
            }
        }
        return $results;
    }

    
    public function totalExamScore($term)
    {
        $total = 0;
        foreach ($this->sectionClassStudentTerms as $sectionClassStudentTerm) {
            if($sectionClassStudentTerm->academicSessionTerm->term->id == $term->id){
                foreach ($sectionClassStudentTerm->studentResults as $studentResult) {
                    if(is_numeric($studentResult->total)){
                        $total = $total + $studentResult->total;
                    }
                }
            }
        }
        return $total;
    }

}
