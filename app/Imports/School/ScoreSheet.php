<?php

namespace App\Imports\School;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use App\Models\SectionClassSubject;
use App\Models\SubjectTeacherTermlyUpload;
use App\Models\Term;
use App\Models\Student;
use App\Models\AcademicSession;
use App\Events\ResultUploaded;

class ScoreSheet implements ToModel
{
    protected $sectionClassSubject;

    public function __construct(SectionClassSubject $classSubject, Term $term)
    {
        $this->sectionClassSubject = $classSubject;
        $this->term = $term;
    }
    /**
    * @param Collection $collection
    */
    public function model(array $row)
    {
       $session = AcademicSession::find(1);

        if(isset($row[2]) && $this->getThisStudent($row[2])){
            $subjectTeacherTermlyUpload = SubjectTeacherTermlyUpload::firstOrCreate([
                'term_id'=>$this->term->id,
                'academic_session_term_id'=>$session->currentSessionTerm()->id,
                'section_class_subject_teacher_id'=>$this->sectionClassSubject->activeSectionClassSubjectTeacher()->id,
            ]);
            if($this->getThisStudent($row[2])->currentSessionTerm()){
                $result = $subjectTeacherTermlyUpload->studentResults()->firstOrCreate([
                    'section_class_student_term_id'  => $this->getThisStudent($row[2])->currentSessionTerm()->id,
                ]);
            
                if($row[3] == null || !is_numeric($row[3])){
                    $row[3] = 0;
                }

                if($row[4] == null || !is_numeric($row[4])){
                    $row[4] = 0;
                }
                if($row[5] == null || !is_numeric($row[5])){
                    $row[5] = 0;
                } 

                $result->update([ 
                    'first_ca' => $row[3],
                    'second_ca' => $row[4],
                    'exam' => $row[5],
                ]);
                $result->updateTotalAndComputeGrade();
                event(new ResultUploaded($result)); 
            }
            $subjectTeacherTermlyUpload->computeAndSaveUploadAverage();   
        }
    }
    
    public function getThisStudent($admissionNo)
    {
        return Student::where('admission_no',$admissionNo)->first();
    }
}
