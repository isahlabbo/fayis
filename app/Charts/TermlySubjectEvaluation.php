<?php

namespace App\Charts;

use ConsoleTVs\Charts\Classes\Chartjs\Chart;
use App\Models\SectionClass;

class TermlySubjectEvaluation extends Chart
{
    protected array $levels = [];
    protected ?int $sectionId = null;

    // ✅ Constructor with sectionId
    public function __construct(?int $sectionId = null)
    {
        parent::__construct();
        $this->sectionId = $sectionId;
    }

    public function build(): void
    {
        // 1) Get classes (levels) filtered by sectionId if provided
        $classes = $this->getAllClasses();
        if ($this->sectionId) {
            $classes = array_filter($classes, fn($c) => $c->section_id == $this->sectionId);
        }

        $this->levels = array_map(fn($c) => $c->name, $classes);
        $this->labels($this->levels);

        // 2) Get all unique subjects in these classes
        $subjects = $this->getAllSubjects($classes);

        // 3) Prepare colors
        $colors = $this->randomColors(count($subjects));

        // 4) Build dataset for each subject
        foreach ($subjects as $index => $subjectName) {
            $values = [];
            foreach ($classes as $sectionClass) {
                $values[] = $sectionClass->getThisSubjectAverageScore($subjectName); // Replace with your real data query
            }

            $color = $colors[$index % count($colors)];

            $this->dataset($subjectName, 'bar', $values)
                 ->color($color)
                 ->backgroundcolor($this->hexToRgba($color, 0.2))
                 ->options(['fill' => true, 'lineTension' => 0.2]);
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

    private function getAllClasses(): array
{
    if ($this->sectionId) {
        // Only get classes belonging to the given sectionId
        return SectionClass::where('section_id', $this->sectionId)
                           ->orderBy('id')
                           ->get()
                           ->all();
    }

}

    private function getAllSubjects(array $classes): array
    {
        $names = [];
        foreach ($classes as $sectionClass) {
            foreach ($sectionClass->sectionClassSubjects as $scs) {
                $subjectName = $scs->subject->name ?? null;
                if ($subjectName && !in_array($subjectName, $names)) $names[] = $subjectName;
            }
        }
        return $names;
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
