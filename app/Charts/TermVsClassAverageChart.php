<?php

namespace App\Charts;

use ConsoleTVs\Charts\Classes\Chartjs\Chart;
use App\Models\SectionClass;
use App\Models\Term;
use App\Models\TermlyClassAveraging;

class TermVsClassAverageChart extends Chart
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
        // 1️⃣ Get classes
        $classesQuery = SectionClass::orderBy('id');
        if ($this->sectionId) {
            $classesQuery->where('section_id', $this->sectionId);
        }
        $classes = $classesQuery->get();
        $classLabels = $classes->pluck('name')->toArray();
        $this->labels($classLabels);

        // 2️⃣ Get all terms (we focus on one term for precomputed table)
        $terms = [Term::find($this->termId)];
        $colors = $this->randomColors(count($terms));

        // 3️⃣ Loop through terms
        foreach ($terms as $index => $term) {
            $averageValues = [];
            $totalValues = [];

            foreach ($classes as $class) {
                $record = TermlyClassAveraging::where([
                    'academic_session_id' => $this->academicSessionId,
                    'term_id' => $term->id,
                    'section_class_id' => $class->id,
                ])->first();

                $averageValues[] = $record ? $record->class_average : 0;
                $totalValues[]   = $record ? $record->total_possible : 0;
            }

            $color = $colors[$index];

            // 🔹 Dataset: Average Performance (%)
            $this->dataset($term->name . ' (Average)', 'line', $averageValues)
                ->color($color)
                ->backgroundcolor($this->hexToRgba($color, 0.2))
                ->options(['fill' => true, 'lineTension' => 0.2]);

            // 🔹 Dataset: Total Marks
            $this->dataset($term->name . ' (Total Marks)', 'line', $totalValues)
                ->color($color)
                ->backgroundcolor($this->hexToRgba($color, 0.05))
                ->options(['fill' => true, 'lineTension' => 0.2, 'borderDash' => [5, 5]]);
        }
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
