<?php

namespace App\Charts;

use ConsoleTVs\Charts\Classes\Chartjs\Chart;
use App\Models\Teacher;
use App\Models\TeachersClassSubjectComparison;

class TeachersComparisonSubjectClassChart extends Chart
{
    protected int $academicSessionId;
    protected int $termId;

    public function __construct(int $sectionId, int $academicSessionId, int $termId)
    {
        parent::__construct();
        $this->academicSessionId = $academicSessionId;
        $this->termId = $termId;
        $this->sectionId = $sectionId;
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

        // 2️⃣ Teachers as X-axis
        $teachers = $records
            ->pluck('teacher')
            ->unique('id')
            ->values();

        $labels = $teachers
            ->map(fn ($t) => $t->user->name ?? 'Unknown')
            ->toArray();

        $this->labels($labels);

        // 3️⃣ Unique Subject + Class combinations
        $subjectClassKeys = $records
            ->map(fn ($r) =>
                $r->subject->name . ' (' . $r->sectionClass->name . ')'
            )
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

            $this->dataset($key, 'line', $values)
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
