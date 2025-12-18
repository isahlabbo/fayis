<?php

namespace App\Charts;

use ConsoleTVs\Charts\Classes\Chartjs\Chart;
use App\Models\TermlySubjectEvaluation;
use App\Models\SectionClass;
use App\Models\Subject;

class TermlySubjectEvaluationChart extends Chart
{
    protected ?int $sectionId = null;
    protected int $academicSessionId;
    protected int $termId;

    public function __construct(int $academicSessionId, int $termId, ?int $sectionId = null)
    {
        parent::__construct();
        $this->academicSessionId = $academicSessionId;
        $this->termId = $termId;
        $this->sectionId = $sectionId;
    }

    public function build(): void
    {
        // 1️⃣ Fetch precomputed analytics
        $query = TermlySubjectEvaluation::with(['subject', 'sectionClass'])
            ->where('academic_session_id', $this->academicSessionId)
            ->where('term_id', $this->termId);

        if ($this->sectionId) {
            $query->whereHas('sectionClass', fn($q) => $q->where('section_id', $this->sectionId));
        }

        $records = $query->get();
        if ($records->isEmpty()) return;

        // 2️⃣ Classes as X-axis (levels)
        $classes = $records->pluck('sectionClass')->unique('id')->values();
        $labels = $classes->map(fn($c) => $c->name)->toArray();
        $this->labels($labels);

        // 3️⃣ Unique subjects
        $subjects = $records->pluck('subject.name')->unique()->values();
        $colors = $this->randomColors($subjects->count());

        // 4️⃣ Dataset per subject
        foreach ($subjects as $index => $subjectName) {
            $values = [];
            foreach ($classes as $class) {
                $record = $records->first(fn($r) =>
                    $r->section_class_id === $class->id && $r->subject->name === $subjectName
                );
                $values[] = $record ? $record->average : 0;
            }

            $color = $colors[$index % count($colors)];
            $this->dataset($subjectName, 'bar', $values)
                ->color($color)
                ->backgroundcolor($this->hexToRgba($color, 0.2))
                ->options(['fill' => true, 'lineTension' => 0.2]);
        }
    }

    // -------------------- Helpers --------------------
    private function randomColors(int $count): array
    {
        $colors = [];
        while (count($colors) < $count) {
            $color = sprintf('#%06X', mt_rand(0, 0xFFFFFF));
            if (!in_array($color, $colors)) $colors[] = $color;
        }
        return $colors;
    }

    private function hexToRgba(string $hex, float $alpha = 1.0): string
    {
        $hex = ltrim($hex, '#');
        if (strlen($hex) === 3) $hex = $hex[0].$hex[0].$hex[1].$hex[1].$hex[2].$hex[2];
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));
        return "rgba({$r}, {$g}, {$b}, {$alpha})";
    }
}
