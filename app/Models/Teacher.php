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
        $notUploaded = [];
        $inProgress = [];
        $submittedToClassMaster = [];
        $submittedToExamOffice = [];
        $published = [];

        foreach ($this->sectionClassSubjectTeachers->where('status','Active') as $sectionClassSubjectTeacher) {
            $allocated[] = $sectionClassSubjectTeacher;
            $status = $sectionClassSubjectTeacher->currentTermUploadStatus();
            if ($status === -1) {
                $notUploaded[] = $sectionClassSubjectTeacher;
            } elseif ($status === 0) {
                $inProgress[] = $sectionClassSubjectTeacher;
            } elseif ($status === 1) {
                $submittedToClassMaster[] = $sectionClassSubjectTeacher;
            }elseif ($status === 2) {
                $submittedToExamOffice[] = $sectionClassSubjectTeacher;
            }elseif($status === 3){
                $published[] = $sectionClassSubjectTeacher;
            }
        }

        return [
            'not_uploaded' => $notUploaded,
            'in_progress' => $inProgress,
            'submitted_to_class_master' => $submittedToClassMaster,
            'submitted_to_exam_office' => $submittedToExamOffice,
            'published' => $published,
            'allocated' => $allocated,
        ];
    }
}   