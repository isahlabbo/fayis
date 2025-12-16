<?php

use App\Models\SectionClassSubjectTeacher;
use App\Models\StudentResult;

public static function teacherSubjectClassPercentage(
    int $teacherId,
    int $subjectId,
    int $classId,
    int $termId,
    int $maxTotal = 100
): float {

    // Get the pivot record (teacher teaches subject in class)
    $pivot = SectionClassSubjectTeacher::where([
        'teacher_id' => $teacherId,
        'subject_id' => $subjectId,
        'section_class_id' => $classId,
    ])->first();

    if (!$pivot) {
        return 0;
    }

    // Get all student results uploaded by this teacher for this term
    $results = StudentResult::whereHas('subjectTeacherTermlyUpload', function ($q) use ($pivot, $termId) {
        $q->where('section_class_subject_teacher_id', $pivot->id)
          ->where('term_id', $termId);
    })->get();

    if ($results->isEmpty()) {
        return 0;
    }

    $studentsCount = $results->count();
    $totalObtained = $results->sum('total');
    $totalPossible = $studentsCount * $maxTotal;

    return round(($totalObtained / $totalPossible) * 100, 2);
}
