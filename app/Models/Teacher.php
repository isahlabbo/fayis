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

        foreach ($this->sectionClassSubjectTeachers->where('status','Active') as $sectionClassSubjectTeacher) {
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
                $submittedToClassMaster[] = $sectionClassSubjectTeacher;
            }elseif($status === 3){
                $published[] = $sectionClassSubjectTeacher;
                $submittedToClassMaster[] = $sectionClassSubjectTeacher;
                $submittedToExamOffice[] = $sectionClassSubjectTeacher;
            }
        }

        if(count($notAttempted) === count($allocated)) {
            $remark = 'No Upload';
            $tableRowClass = 'bg-danger text-dark';
        } elseif(count($published) === count($allocated)) {
            $remark = 'All Published';
            $tableRowClass = 'bg-success text-dark';
        } elseif( count($submittedToClassMaster) === count($allocated)) {
            $remark = 'All Submitted';
            $tableRowClass = 'bg-info text-dark';
        }elseif(count($submittedToClassMaster) !== count($allocated)) {
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
            'table_row_class' => $tableRowClass
        ];
    }
}   