<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Charts\TeachingEvaluation;
use App\Services\Diagnose\ClassMode as Diagnosable;

class SectionClass extends BaseModel
{
    use Diagnosable;
    
    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function sectionClassGroup()
    {
        return $this->belongsTo(SectionClassGroup::class);
    }

    public function resultType()
    {
        return $this->belongsTo(ResultType::class);
    }

    public function sectionClassStudents()
    {
        return $this->hasMany(SectionClassStudent::class);
    }

    public function sectionClassPayments()
    {
        return $this->hasMany(SectionClassPayment::class);
    }

    public function sectionClassFees() 
    {
        return $this->hasMany(SectionClassFee::class);
    }

    public function sectionClassSubjects()
    {
        return $this->hasMany(SectionClassSubject::class);
    }

    public function sectionClassTeachers()
    {
        return $this->hasMany(SectionClassTeacher::class);
    }

    public function sectionClassReservedAdmissionNos()
    {
        return $this->hasMany(SectionClassReservedAdmissionNo::class);
    }

    public function numberofUnpublishedResults() {
        $count = 0;
        foreach($this->sectionClassStudents->where('status', 'Active') as $studentInClass){
            foreach($studentInClass->sectionClassStudentTerms as $studentTerm){
                if(!$studentTerm->sectionClassStudentTermResultPublish){
                    $count++;
                }
            }
        }
        return $count;
    }

    public function getStudentSessionResultsForTerm($sessionId, $termId)
    {
        $results = [];
        foreach($this->sectionClassStudents as $studentInClass){
            foreach($studentInClass->sectionClassStudentTerms as $studentTerm){
                if($studentTerm->academicSessionTerm->academic_session_id == $sessionId && $studentTerm->academicSessionTerm->term_id == $termId){
                    foreach($studentTerm->studentResults as $result){
                        $results[] = $result;
                    }
                }
            }
        }
        return collect($results);
    }

    public function getClassTermAverage($termId){
        $count = 0;
        $totalScore = 0;
        foreach($this->sectionClassStudents->where('status', 'Active') as $student){
            foreach($student->sectionClassStudentTerms as $studentTerm){
                if($studentTerm->academicSessionTerm->term_id == $termId){
                    $totalScore += $studentTerm->studentTotalScore();
                    $count++;
                }
            }
        }
        if($count == 0){
            $count = 1;
        }
        return $totalScore/$count;
    }

    public function updateAndGetAllActiveStudentResultForThisTerms($uploadId = null) {
        // enusure all active sectionClassStudents has 3 sectionClassStudentTerms and eachsectionClassStudentTerm has studentResults

        $results = $this->ensureStudentHasAllTerms($uploadId);
        
        return $results;
    }

    function ensureStudentHasAllTerms($uploadId = null) {
        $results = [];
       
        foreach($this->sectionClassStudents->where('status','Active') as $sectionClassStudent){
            $results[] = $sectionClassStudent->ensureHasAllAcademicSessionTerm( $uploadId);
        }

        return $results;
    }


    public function schoolFees($termId, $genderId) {
        $fees = 0;
        foreach($this->sectionClassFees->where('fee_id', 1) as $sectionClassFee){
            foreach($sectionClassFee->sectionClassFeeItems as $feeItem){
                if($feeItem->gender_id == $genderId && $feeItem->term_id == $termId){
                    $fees+=$feeItem->amount;
                }
            }
        }
        return $fees;
    }

    public function materialFees($genderId) {
        $fees = 0;
        foreach($this->sectionClassFees->where('fee_id', 2) as $sectionClassFee){
            foreach($sectionClassFee->sectionClassFeeItems as $feeItem){
                if($feeItem->gender_id == $genderId){
                    $fees+=$feeItem->amount;
                }
            }
        }
        return $fees;
    }

    public function hasThisSubject(Subject $subject = null)
    {
        $flag = false;
        foreach($this->sectionClassSubjects as $classSubject){
            if($classSubject->subject->id == $subject->id){
                $flag = true;
            }
        }
        return $flag;
    }
    public function stateSchoolFee($term,$state,$studentType, $gender)
    {
        $fee = 0;

        foreach($this->sectionClassPayments
        ->where('term_id',$term->id)
        ->where('gender_id', $gender)
        ->where('student_type_id',$studentType) as $payment){
            $fee += $payment->amount;
        }

        foreach($this->sectionClassPayments
        ->where('gender_id', 3)
        ->where('student_type_id',$studentType)
        ->where('term_id',$term->id) as $payment){
            $fee += $payment->amount;
        }
        
        return $fee;
    }

    public function totalFee($term,$student)
    {
        $fee = 0;
        
        foreach($this->sectionClassPayments
        ->where('term_id',$term->id)
        ->where('gender_id', $student->gender->id)
        ->where('student_type_id',$student->student_type_id) as $payment){
            $fee += $payment->amount;
        }
        
        foreach($this->sectionClassPayments
        ->where('gender_id', 3)
        ->where('student_type_id',$student->student_type_id)
        ->where('term_id',$term->id) as $payment){
            $fee += $payment->amount;
        }
        
        return $fee;
    }

    public function nextClass()
    {
        $class = $this->section->sectionClasses
        ->where('section_class_group_id',$this->section_class_group_id)
        ->where('year_sequence',$this->getNextClassSequence())->first();
        if(!$class){
            $class = $this->section->nextSection()->sectionClasses->where('year_sequence','First')->first();
        }
        return $class;
    }

    public function activeStudentIds()
    {
        $ids = [];
        foreach($this->sectionClassStudents->where('status','Active') as $student){
            $ids[] = $student->id;
        }
        return $ids;
    }
    
    public function hasNextClass()
    {
        return $this->section->sectionClasses
        ->where('section_class_group_id',$this->section_class_group_id)
        ->where('year_sequence',$this->getNextClassSequence())->first();
    }

    public function getNextClassSequence()
    {
        switch ($this->year_sequence) {
            case 'First':
                $sequence = 'Second';
                break;
            case 'Second':
                $sequence = 'Third';
                break;
            case 'Third':
                $sequence = 'Forth';
                break;
            case 'Forth':
                $sequence = 'Fifth';
                break;
            default:
                $sequence = 'Last';
                break;
        }
        return $sequence;
    }
    public function canPublishResult()
    {
        foreach($this->sectionClassStudents->where('status','Active') as $sectionClassStudent){
            if(!$sectionClassStudent->currentStudentTerm()->sectionClassStudentTermResultPublish){
                return true;
            }
        }
        return false;
    }

    
    public function subjectResultUploads()
    {
        $uploadedResult = [];
        $awaitingResult = [];
        foreach ($this->sectionClassSubjects as $sectionClassSubject) {
            if($upload = $sectionClassSubject->hasCurrentTermUpload()){
                $uploadedResult[] = $upload;
            }else{
                $awaitingResult[] = $sectionClassSubject;
            }
        }

        return ['uploaded' => $uploadedResult, 'awaiting' => $awaitingResult];
    }

    public function sectionClassTermlyExams()
    {
        return $this->hasMany(SectionClassTermlyExam::class);
    }


    public function updateAllStudentTerm()
    {
        
        foreach ($this->sectionClassStudents->where('status','Active') as $sectionClassStudent) {
            $sectionClassStudent->updateActiveTerm();
        }
    }

    function getThisSubjectAverageScore($subjectName) {
        $average = 0;
        $subject = Subject::where('name', $subjectName)->first();
        $classSubject = $this->sectionClassSubjects->where('subject_id', $subject->id)->first();
        if($classSubject && $classSubject->activeSectionClassSubjectTeacher()){
            $subjectUpload = $classSubject->activeSectionClassSubjectTeacher()->subjectTeacherTermlyUploads->where('academic_session_id', $this->currentSession()->id)->where('term_id',$this->currentSessionTerm()->term->id)->first();
            if($subjectUpload){
                $average = $subjectUpload->average();
            }
            
        }
        
        return $average;
    }
    public function studentPosition($sectionClassStudentTerm)
    {
        if(config('app.nursery_class_position') == true && $this->section->name == 'NURSERY'){
            $score = $sectionClassStudentTerm->studentTermTotalScore();
            $totalMarks = count($this->sectionClassSubjects)*100;
            $percentage = 100 * ($score/$totalMarks);
            
            if($percentage >= 90){
                $position = 'Distinction';
            }elseif ($percentage >= 70) {
                $position = 'Excellent';
            }elseif ($percentage >= 60) {
                $position = 'Very Good';
            }elseif ($percentage >= 50) {
                $position = 'Good';
            }elseif ($percentage >= 40) {
                $position = 'Pass';
            }else {
                $position = 'Poor';
            }
            return $position;
        }else{
            $allStudentsScoreInTheClass = [];
            foreach($this->sectionClassStudents->where('status','Active') as $sectionClassStudent){
                foreach($sectionClassStudent->sectionClassStudentTerms as $studentTerm){
                    if($studentTerm->academicSessionTerm->term->id == $sectionClassStudentTerm->academicSessionTerm->term->id){
                        $allStudentsScoreInTheClass[] = $studentTerm->studentTermTotalScore();
                    }
                }
            }
            // remove the duplicate score from the array
            array_unique($allStudentsScoreInTheClass);
        
            // sort array decending order
            rsort($allStudentsScoreInTheClass);
            foreach($allStudentsScoreInTheClass as $key => $value){
                if($sectionClassStudentTerm->sectionClassStudent->totalExamScore($sectionClassStudentTerm->academicSessionTerm->term) == $value){
                    return $this->getValidPositionName(($key+1));
                }
            }
        }
    }

    public function getValidPositionName($position)
    {
        switch ($position) {
            case '1':
                $position = $position.'ST';
                break;
            case '2':
                $position = $position.'ND';
                break;
            case '3':
                $position = $position.'RD';
                break;
            case '21':
                $position = $position.'ST';
                break;
            case '22':
                $position = $position.'ND';
                break;
            case '23':
                $position = $position.'RD';
                break;
            case '31':
                $position = $position.'ST';
                break;
            case '32':
                $position = $position.'ND';
                break;
            case '33':
                $position = $position.'RD';
                break;  
            case '41':
                $position = $position.'ST';
                break;
            case '42':
                $position = $position.'ND';
                break;
            case '43':
                $position = $position.'RD';
                break;           
            default:
                $position = $position.'TH';
                break;
        }
        return $position;
    }
    public function classAverage($term)
    {
        $classStudentAverages = 0;
        $count = 0;
        foreach ($this->sectionClassStudents->where('status','Active') as $sectionClassStudent) {
            foreach($sectionClassStudent->sectionClassStudentTerms as $sectionClassStudentTerm){
                if($term->id == $sectionClassStudentTerm->academicSessionTerm->term->id){
                    $classStudentAverages = $classStudentAverages + $sectionClassStudentTerm->studentAverage();
                    $count++;
                }
            }
        }
        if($count<1){
            $count = 1;
        }
        return number_format($classStudentAverages/$count,2);
    }

    public function activeClassTeacher()
    {
        return $this->sectionClassTeachers->where('status','Active')->first();
    }

    public function currentStudents()
    {
        $students = [];
        foreach ($this->sectionClassStudents->where('status','Active') as $classStudent) {
            $students[] = $classStudent->student;
        }
        return $students;
    }

    public function reserveNumber($admissionNo)
    {
        $this->sectionClassReservedAdmissionNos()->create([
            'academic_session_id'=>$this->currentSession()->id,
            'admission_no'=>$admissionNo
            ]);
    }
    public function generateAdmissionNo()
    {
        $admissionYear = substr(date('Y') - ($this->year_sequence - 1), 2, 2);
        $code = config('app.code');
        $classInitial = $this->section->class_tag;

        for($number = 1; $number <= $this->capacity*2; $number++){
            // format number into 001 format
            $number = $this->formatSerialNo($number);
            $admissionNo = $code.'/'.$admissionYear.$classInitial.'/'.$number;
            // check if admission no exist
            $existing = Student::where('admission_no',$admissionNo)->first();
            if(!$existing){
                return $admissionNo;
            }
        }
        
        return null;
    }

    public function getAdmissionSerialNo($number)
    {
        return $this->formatSerialNo($number ?? count($this->classAdmissionSession()->classAdmissions($this))+1);
    }

    public function classAdmissionSession()
    {
        $start = $this->getAdmissionYear();
        $end = $start+1;
        return AcademicSession::firstOrCreate(['name'=>$start.'/'.$end]);
    }

    public function getAdmissionYear()
    {
        
        $currentYear = date('Y');
        
        if($currentYear == substr($this->currentSession()->name,'5',)){
            $currentYear = $currentYear - 1;
        }
        $year = null;
        switch ($this->year_sequence) {
            case 'First':
                $year = $currentYear;
                break;
            case 'Second':
                $year = $currentYear - 1;
                break;
            case 'Third':
                $year = $currentYear - 2;
                break;
            case 'Forth':
                $year = $currentYear - 3;
                break;
            case 'Fifth':
                $year = $currentYear - 4;
                break;
            case 'Sixth':
                    $year = $currentYear - 5;
                    break;            
            default:
                $year = $currentYear;
                break;
        }
        return $year;
    }

    public function updateAdmissionNo()
    {
        $count = 1;
        foreach ($this->sectionClassStudents->where('status','Active') as $sectionClassStudent) {
            $sectionClassStudent->student->update(['admission_no'=>$this->generateAdmissionNo($count)]);
            $count++;
        }
    }

    public function formatSerialNo($number)
    {
        if($number < 10){
            $number = '00'.$number;
        }elseif($number < 100){
            $number = '0'.$number;
        }
        return $number;
    }

    public function availableResultUploads($sessionId = null, $termId = null)
    {
        $uploadCounts = 0;
        foreach($this->sectionClassSubjects as $sectionClassSubject){
            $uploadCounts = $uploadCounts + count($sectionClassSubject->availableResultUploads($sessionId, $termId));
        }
        return $uploadCounts;
    }
}
