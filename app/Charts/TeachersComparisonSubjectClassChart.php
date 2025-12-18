<?php

namespace App\Charts;

use ConsoleTVs\Charts\Classes\Chartjs\Chart;
use App\Models\Teacher;
use App\Models\TeachersClassSubjectComparison;

class TeachersComparisonSubjectClassChart extends Chart
{
    protected int $academicSessionId;
    protected int $termId;
    protected ?int $teacherId = null;

    public function __construct(int $academicSessionId, int $termId, int $sectionId, ?int $teacherId = null)
    {
        parent::__construct();
        $this->academicSessionId = $academicSessionId;
        $this->termId = $termId;
        $this->sectionId = $sectionId;
        $this->teacherId = $teacherId;
    }

    public function build(): void
    {
        // 1️⃣ Fetch analytics records
        $records = TeachersClassSubjectComparison::with([
                'teacher.user',
                'subject',
                'sectionClass'
            ])
            ->where([
                'section_id' => $this->sectionId,
                'academic_session_id' => $this->academicSessionId,
                'term_id' => $this->termId
            ])
            ->get();

        if ($records->isEmpty()) {
            return;
        }

        // If a specific teacher is requested, render a chart for that teacher only
        $teachers = $records->pluck('teacher')->unique('id')->values();

        if ($this->teacherId) {
            $teacher = $teachers->firstWhere('id', $this->teacherId);
            if (! $teacher) {
                return; // no data for requested teacher
            }

            $subjectClassKeys = $records
                ->map(fn ($r) => $r->subject->name . ' (' . $r->sectionClass->name . ')')
                ->unique()
                ->values();

            $labels = $subjectClassKeys->toArray();
            $this->labels($labels);

            $values = [];
            foreach ($subjectClassKeys as $key) {
                $row = $records->first(function ($r) use ($key) {
                    $label = $r->subject->name . ' (' . $r->sectionClass->name . ')';
                    return $label === $key;
                });

                $values[] = $row && $row->teacher_id === $this->teacherId ? $row->percentage : 0;
            }

            $color = $this->randomColors(1)[0];

            $this->dataset($teacher->user->name ?? 'Teacher', 'bar', $values)
                ->backgroundcolor($this->hexToRgba($color, 0.7))
                ->color($color);

            $count = count($labels);

            $this->dataset('Excellent (85%)', 'line', array_fill(0, $count, 85))
                ->color('#2ECC71')
                ->options([
                    'borderDash' => [5, 5],
                    'pointRadius' => 0,
                    'fill' => false
                ]);

            $this->dataset('Pass (50%)', 'line', array_fill(0, $count, 50))
                ->color('#E74C3C')
                ->options([
                    'borderDash' => [5, 5],
                    'pointRadius' => 0,
                    'fill' => false
                ]);

            return;
        }

        // 2️⃣ Teachers as X-axis for multi-teacher comparison
        $labels = $teachers->map(fn ($t) => $t->user->name ?? 'Unknown')->toArray();
        $this->labels($labels);

        // 3️⃣ Unique Subject + Class combinations
        $subjectClassKeys = $records
            ->map(fn ($r) => $r->subject->name . ' (' . $r->sectionClass->name . ')')
            ->unique()
            ->values();

        $colors = $this->randomColors($subjectClassKeys->count());

        // 4️⃣ Dataset per Subject-Class
        foreach ($subjectClassKeys as $index => $key) {

            $values = [];

            foreach ($teachers as $teacher) {
                $row = $records->first(function ($r) use ($teacher, $key) {
                    $label = $r->subject->name . ' (' . $r->sectionClass->name . ')';
                    return $r->teacher_id === $teacher->id && $label === $key;
                });

                $values[] = $row ? $row->percentage : 0;
            }

            $color = $colors[$index];

            $this->dataset($key, 'bar', $values)
                ->backgroundcolor($this->hexToRgba($color, 0.7))
                ->color($color);
        }

        // 5️⃣ Reference performance lines
        $count = count($labels);

        $this->dataset('Excellent (85%)', 'line', array_fill(0, $count, 85))
            ->color('#2ECC71')
            ->options([
                'borderDash' => [5, 5],
                'pointRadius' => 0,
                'fill' => false
            ]);

        $this->dataset('Pass (50%)', 'line', array_fill(0, $count, 50))
            ->color('#E74C3C')
            ->options([
                'borderDash' => [5, 5],
                'pointRadius' => 0,
                'fill' => false
            ]);
    }

    // -------------------- HELPERS --------------------

    private function randomColors(int $count): array
    {
        $colors = [];
        while (count($colors) < $count) {
            $color = sprintf('#%06X', mt_rand(0, 0xFFFFFF));
            if (!in_array($color, $colors)) {
                $colors[] = $color;
            }
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
