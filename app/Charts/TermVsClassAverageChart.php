<?php

namespace App\Charts;

use ConsoleTVs\Charts\Classes\Chartjs\Chart;
use App\Models\SectionClass;
use App\Models\Term;
use App\Models\StudentResult;
use App\Services\AcademicPerformanceService;

class TermVsClassAverageChart extends Chart
{
    protected ?int $sectionId = null;

    // Max mark per subject
    protected int $maxMarkPerSubject = 100;

    public function __construct(?int $sectionId = null)
    {
        parent::__construct();
        $this->sectionId = $sectionId;
    }

    public function build(): void
    {
        // 1️⃣ Get classes
        $classes = $this->getClasses();
        $classLabels = array_map(fn($c) => $c->name, $classes);
        $this->labels($classLabels);

        // 2️⃣ Get terms
        $terms = Term::orderBy('id')->get();
        $colors = $this->randomColors($terms->count());

        // 3️⃣ Loop through terms
        foreach ($terms as $index => $term) {
            $percentageValues = [];
            $totalValues = [];

            foreach ($classes as $class) {
                // Total marks obtained for this class in this term
                $totalObtained = $class->getClassTermAverage($term->id);

                // Total possible marks
                $subjectsCount = $class->sectionClassSubjects()->count();
                
                $totalPossible = $subjectsCount * $this->maxMarkPerSubject;

                $percentageValues[] = $totalObtained;
                $totalValues[] = $totalPossible;
            }

            $color = $colors[$index];

            // 🔹 Dataset: Average Performance (%)
            $this->dataset($term->name . ' (Average)', 'line', $percentageValues)
                ->color($color)
                ->backgroundcolor($this->hexToRgba($color, 0.2))
                ->options([
                    'fill' => true,
                    'lineTension' => 0.2,
                ]);

            // 🔹 Dataset: Total Marks
            $this->dataset($term->name . ' (Total Marks)', 'line', $totalValues)
                ->color($color)
                ->backgroundcolor($this->hexToRgba($color, 0.05))
                ->options([
                    'fill' => true,
                    'lineTension' => 0.2,
                    'borderDash' => [5, 5]
                ]);
        }

        
    }

    private function getClasses(): array
    {
        if ($this->sectionId) {
            return SectionClass::where('section_id', $this->sectionId)
                ->orderBy('id')
                ->get()
                ->all();
        }

        return SectionClass::orderBy('id')->get()->all();
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
