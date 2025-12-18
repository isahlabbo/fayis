<?php

namespace App\Charts;

use ConsoleTVs\Charts\Classes\Chartjs\Chart;
use App\Models\Teacher;
use App\Models\TermlyTeacherEffectiveIndex;

class TeacherEffectivenessIndexChart extends Chart
{
    protected int $academicSessionId;
    protected int $termId;

    public function __construct(int $academicSessionId, int $termId)
    {
        parent::__construct();
        $this->academicSessionId = $academicSessionId;
        $this->termId = $termId;
    }

    public function build(): void
    {
        // 1️⃣ Fetch analytics data (precomputed)
        $analytics = TermlyTeacherEffectiveIndex::with('teacher.user')
            ->where([
                'academic_session_id' => $this->academicSessionId,
                'term_id' => $this->termId
            ])
            ->orderByDesc('effectiveness_index')
            ->get();

        // Safety check
        if ($analytics->isEmpty()) {
            return;
        }

        // 2️⃣ Labels (Teacher names)
        $labels = $analytics->map(
            fn ($row) => $row->teacher->user->name ?? 'Unknown'
        )->toArray();

        $this->labels($labels);

        // 3️⃣ Effectiveness values
        $values = $analytics->pluck('effectiveness_index')->toArray();

        $this->dataset('Teacher Effectiveness Index (%)', 'bar', $values)
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
