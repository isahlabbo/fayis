<?php

namespace App\Charts;

use ConsoleTVs\Charts\Classes\Chartjs\Chart;
use App\Models\Teacher;
use App\Models\SectionClassSubjectTeacher;
use App\Models\StudentResult;

class TeachersComparisonSubjectClassChart extends Chart
{
    protected int $termId;

    public function __construct(int $termId)
    {
        parent::__construct();
        $this->termId = $termId;
    }

    public function build(): void
    {
        // 1️⃣ Teachers as X-axis labels
        $teachers = Teacher::orderBy('id')->get();
        $labels = $teachers->map(fn($t) => $t->user->name)->toArray();
        $this->labels($labels);

        // 2️⃣ Active teaching assignments
        $assignments = SectionClassSubjectTeacher::where('status', 'Active')
            ->with(['teacher.user', 'sectionClassSubject.subject', 'sectionClassSubject.sectionClass'])
            ->get();
        $validAssignments = $assignments->filter(function ($a) {
            return $a->sectionClassSubject
                && $a->sectionClassSubject->subject
                && $a->sectionClassSubject->sectionClass;
        });

        $uniqueSubjectsClasses = $validAssignments
            ->map(fn($a) =>
                $a->sectionClassSubject->subject->name .
                ' (' . $a->sectionClassSubject->sectionClass->name . ')'
            )
            ->unique()
            ->values();

        

        $colors = $this->randomColors($uniqueSubjectsClasses->count());

        // 4️⃣ Dataset per subject-class
        foreach ($uniqueSubjectsClasses as $index => $label) {
            $values = [];

            foreach ($teachers as $teacher) {
                // Find assignment for this teacher + subject-class
                $assignment = $validAssignments->first(function($a) use ($teacher, $label) {
                    $subClassLabel = $a->sectionClassSubject->subject->name
                        . ' (' . $a->sectionClassSubject->sectionClass->name . ')';
                    return $a->teacher_id === $teacher->id && $subClassLabel === $label;
                });

                if (!$assignment) {
                    $values[] = 0; // teacher doesn't teach this subject-class
                } else {
                    $values[] = $this->teacherSubjectClassPercentage(
                        $teacher->id,
                        $assignment->section_class_subject_id,
                        $assignment->sectionClassSubject->section_class_id,
                        $this->termId
                    );
                }
            }

            $color = $colors[$index];

            $this->dataset($label, 'bar', $values)
                ->color($color)
                ->backgroundcolor($this->hexToRgba($color, 0.7));
        }

        // 5️⃣ Reference lines for management insight
        $count = count($labels);

        $this->dataset('Excellent (85%)', 'line', array_fill(0, $count, 85))
            ->color('#2ECC71')
            ->options(['borderDash' => [5, 5], 'pointRadius' => 0]);

        $this->dataset('Pass (50%)', 'line', array_fill(0, $count, 50))
            ->color('#E74C3C')
            ->options(['borderDash' => [5, 5], 'pointRadius' => 0]);
    }

    // -------------------- Helper Methods --------------------

    private function teacherSubjectClassPercentage(
        int $teacherId,
        int $sectionClassSubjectId,
        int $classId,
        int $termId,
        int $maxTotal = 100
    ): float {
        // Pivot: teacher teaches subject-class
        $pivot = SectionClassSubjectTeacher::where([
            'teacher_id' => $teacherId,
            'section_class_subject_id' => $sectionClassSubjectId,
        ])->first();

        if (!$pivot) return 0;

        // All results uploaded by this teacher for this term
        $results = StudentResult::whereHas('subjectTeacherTermlyUpload', function ($q) use ($pivot, $termId) {
            $q->where('section_class_subject_teacher_id', $pivot->id)
              ->where('term_id', $termId);
        })->get();

        if ($results->isEmpty()) return 0;

        $studentsCount = $results->count();
        $totalObtained = $results->sum('total');
        $totalPossible = $studentsCount * $maxTotal;

        return round(($totalObtained / $totalPossible) * 100, 2);
    }

    private function randomColors(int $count): array
    {
        $colors = [];
        while (count($colors) < $count) {
            $color = sprintf('#%06X', mt_rand(0, 0xFFFFFF));
            if (!in_array($color, $colors)) $colors[] = $color;
        }
        return $colors;
    }

    private function hexToRgba(string $hex, float $alpha): string
    {
        $hex = ltrim($hex, '#');
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));
        return "rgba({$r}, {$g}, {$b}, {$alpha})";
    }
}
