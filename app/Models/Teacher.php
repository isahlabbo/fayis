<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends BaseModel
{
    

    public function sectionClassTeachers()
    {
        return $this->hasMany(SectionClassTeacher::class);
    }

    public function sectionClassSubjectTeachers()
    {
        return $this->hasMany(SectionClassSubjectTeacher::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function allocated(){
        $report = $this->resultUploadReport();
        return count($report['allocated']);
    }

    public function submitted(){
        $report = $this->resultUploadReport();
        return count($report['submitted']);
    }

    public function inProgress(){
        $report = $this->resultUploadReport();  
        return count($report['in_progress']);
    }

    public function notAttempted(){
        $report = $this->resultUploadReport();
        return count($report['not_attempted']);
    }

    public function submittedToClassMaster(){
        $report = $this->resultUploadReport();
        return count($report['submitted_to_class_master']) + count($report['submitted_to_exam_office']) + count($report['published']);
    }

    public function submittedToExamOffice(){
        $report = $this->resultUploadReport();
        return count($report['submitted_to_exam_office']) + count($report['published']);
    }

    public function published(){
        $report = $this->resultUploadReport();
        return count($report['published']);
    }

    public function uploadRemark(){
        $report = $this->resultUploadReport();
        return $report['remark'];
    }

    public function resultUploadReport()
    {
        $allocated = [];
        $notAttempted = [];
        $inProgress = [];
        $submittedToClassMaster = [];
        $submittedToExamOffice = [];
        $published = [];
        $remark = null;
        $tableRowClass = '';
        $submitted = [];

        foreach ($this->sectionClassSubjectTeachers->where('status','Active') as $sectionClassSubjectTeacher) {
            
            if($sectionClassSubjectTeacher->sectionClassSubject){
                $allocated[] = $sectionClassSubjectTeacher;
                $status = $sectionClassSubjectTeacher->currentTermUploadStatus();
                if ($status === -1) {
                    $notAttempted[] = $sectionClassSubjectTeacher;
                } elseif ($status === 0) {
                    $inProgress[] = $sectionClassSubjectTeacher;
                } elseif ($status === 1) {
                    $submittedToClassMaster[] = $sectionClassSubjectTeacher;
                }elseif ($status === 2) {
                    $submittedToExamOffice[] = $sectionClassSubjectTeacher;
                }elseif($status === 3){
                    $published[] = $sectionClassSubjectTeacher;
                }

                if($status > 0){
                    $submitted[] = $sectionClassSubjectTeacher;
                }
            }

        }

        if(count($notAttempted) === count($allocated)) {
            $remark = 'No Upload';
            $tableRowClass = 'bg-danger text-dark';
        } elseif(count($published) === count($allocated)) {
            $remark = 'All Published';
            $tableRowClass = 'bg-success text-dark';
        } elseif( count($submitted) === count($allocated)) {
            $remark = 'All Submitted';
            $tableRowClass = 'bg-info text-dark';
        }elseif(count($submitted) < count($allocated)) {
            $remark = 'Incomplete Submission';
            $tableRowClass = 'bg-warning ';
         } elseif(count($inProgress) > 0) {
            $remark = 'In Progress';
            $tableRowClass = 'bg-warning text-dark';
        }

        return [
            'not_attempted' => $notAttempted,
            'in_progress' => $inProgress,
            'submitted_to_class_master' => $submittedToClassMaster,
            'submitted_to_exam_office' => $submittedToExamOffice,
            'published' => $published,
            'allocated' => $allocated,
            'remark'    => $remark,
            'table_row_class' => $tableRowClass,
            'submitted' => $submitted
        ];
    }
}   