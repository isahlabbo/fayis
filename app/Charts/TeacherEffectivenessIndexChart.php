<?php

namespace App\Charts;

use ConsoleTVs\Charts\Classes\Chartjs\Chart;
use App\Models\Teacher;
use App\Models\SectionClassSubjectTeacher;
use App\Models\StudentResult;

class TeacherEffectivenessIndexChart extends Chart
{
    protected int $termId;

    public function __construct(int $termId)
    {
        parent::__construct();
        $this->termId = $termId;
    }

    public function build(): void
    {
        // 1️⃣ Teachers
        $teachers = Teacher::with('user')->orderBy('id')->get();

        $labels = $teachers->map(fn($t) => $t->user->name)->toArray();
        $this->labels($labels);

        // 2️⃣ Calculate TEI per teacher
        $values = [];

        foreach ($teachers as $teacher) {
            $values[] = $this->calculateTeacherEffectiveness($teacher->id);
        }

        // 3️⃣ Main dataset
        $this->dataset('Teacher Effectiveness Index (%)', 'line', $values)
            ->backgroundcolor($this->hexToRgba('#3498DB', 0.7))
            ->color('#3498DB');

        // 4️⃣ Reference lines
        $count = count($labels);

        $this->dataset('Excellent (85%)', 'line', array_fill(0, $count, 85))
            ->color('#2ECC71')
            ->options([
                'borderDash' => [5, 5],
                'pointRadius' => 0,
                'fill' => false
            ]);

        $this->dataset('Needs Improvement (50%)', 'line', array_fill(0, $count, 50))
            ->color('#E74C3C')
            ->options([
                'borderDash' => [5, 5],
                'pointRadius' => 0,
                'fill' => false
            ]);
    }

    // -------------------- CORE LOGIC --------------------

    private function calculateTeacherEffectiveness(
        int $teacherId,
        int $maxTotalPerStudent = 100
    ): float {
        // All subject-class assignments for this teacher
        $assignments = SectionClassSubjectTeacher::where([
                'teacher_id' => $teacherId,
                'status' => 'Active'
            ])->get();

        if ($assignments->isEmpty()) {
            return 0;
        }

        $totalObtained = 0;
        $totalPossible = 0;

        foreach ($assignments as $assignment) {
            $results = StudentResult::whereHas(
                'subjectTeacherTermlyUpload',
                function ($q) use ($assignment) {
                    $q->where('section_class_subject_teacher_id', $assignment->id)
                      ->where('term_id', $this->termId);
                }
            )->get();

            if ($results->isEmpty()) {
                continue;
            }

            $studentsCount = $results->count();

            $totalObtained += $results->sum('total');
            $totalPossible += $studentsCount * $maxTotalPerStudent;
        }

        if ($totalPossible === 0) {
            return 0;
        }

        return round(($totalObtained / $totalPossible) * 100, 2);
    }

    // -------------------- HELPERS --------------------

    private function hexToRgba(string $hex, float $alpha): string
    {
        $hex = ltrim($hex, '#');
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));
        return "rgba({$r}, {$g}, {$b}, {$alpha})";
    }
}
