<?php

namespace App\Charts;

use ConsoleTVs\Charts\Classes\Chartjs\Chart;
use App\Models\CentralAndDisperseResultMeasure;

class SubjectDistributionChart extends Chart
{
    protected int $sessionId;
    protected int $termId;
    protected int $sectionId;
    protected int $classId;

    public function __construct(
        int $sessionId,
        int $termId,
        int $sectionId,
        int $classId
    ) {
        parent::__construct();

        $this->sessionId = $sessionId;
        $this->termId = $termId;
        $this->sectionId = $sectionId;
        $this->classId = $classId;
    }

    public function build(): void
    {
        $records = CentralAndDisperseResultMeasure::where([
                'academic_session_id' => $this->sessionId,
                'term_id' => $this->termId,
                'section_id' => $this->sectionId,
                'section_class_id' => $this->classId,
            ])
            ->with('subjectTeacherTermlyUpload.sectionClassSubject.subject')
            ->get();

        if ($records->isEmpty()) {
            return;
        }

        // 1️⃣ Labels (Subjects)
        $labels = $records->map(
            fn ($r) =>
                $r->subjectTeacherTermlyUpload
                  ->sectionClassSubject
                  ->subject
                  ->name
        )->toArray();

        $this->labels($labels);

        // 2️⃣ Mean scores
        $means = $records->pluck('mean')->toArray();

        // 3️⃣ Standard deviation
        $stdDevs = $records->pluck('standard_deviation')->toArray();

        // 4️⃣ Mean dataset
        $this->dataset('Mean Score', 'bar', $means)
            ->backgroundcolor('rgba(52, 152, 219, 0.7)')
            ->color('#3498DB');

        // 5️⃣ Std deviation dataset
        $this->dataset('Standard Deviation', 'line', $stdDevs)
            ->color('#E74C3C')
            ->backgroundcolor('rgba(231, 76, 60, 0.1)')
            ->options([
                'fill' => false,
                'tension' => 0.3,
                'pointRadius' => 4,
            ]);
    }
}
