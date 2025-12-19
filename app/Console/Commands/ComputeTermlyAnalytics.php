<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

use App\Models\Teacher;
use App\Models\SectionClass;
use App\Models\Subject;
use App\Models\SectionClassSubjectTeacher;
use App\Models\StudentResult;
use App\Models\Section;
use Illuminate\Support\Collection;
use App\Models\CentralAndDisperseResultMeasure;
use App\Models\TermlyTeacherEffectiveIndex;
use App\Models\TeachersClassSubjectComparison;
use App\Models\TermlySubjectEvaluation;
use App\Models\TermlyClassAveraging;

class ComputeTermlyAnalytics extends Command
{
    protected $signature = 'analytics:compute-termly 
                            {academic_session_id : Academic session ID}
                            {term_id : Term ID}';

    protected $description = 'Compute all termly academic analytics';

    public function handle()
    {
        $sessionId = $this->argument('academic_session_id');
        $termId    = $this->argument('term_id');

        $this->info("Starting analytics computation for session {$sessionId}, term {$termId} for the entire school...");
        foreach (Section::all() as $section) {
            $sectionId = $section->id;

            $this->info("Processing Section: {$section->name} ");
            DB::transaction(function () use ($sectionId, $sessionId, $termId) {

                $this->clearOldAnalytics($sectionId, $sessionId, $termId);
                $this->info("Old analytics cleared");

                $this->computeTeacherEffectiveness($sectionId, $sessionId, $termId);
                $this->info("✅ Teacher Effectiveness Index computed.");

                $this->computeTeacherClassSubjectComparison($sectionId, $sessionId, $termId);
                $this->info("✅ Teacher × Class × Subject Comparison computed.");

                $this->computeSubjectEvaluation($sectionId, $sessionId, $termId);
                $this->info("✅ Termly Subject Evaluation computed.");
                
                $this->computeClassAveraging($sectionId, $sessionId, $termId);
                $this->info("✅ Termly Class Averaging computed.");

                $this->computeCentralAndDispersionMeasures($sectionId, $sessionId, $termId);
                $this->info("✅ Central and Dispersion Measures computed.");
            });
        }

        $this->info('✅ Analytics computation completed successfully!');
    }

    // --------------------------------------------------
    // 🔥 Clear old analytics for the session+term
    // --------------------------------------------------
    private function clearOldAnalytics($sectionId, $sessionId, $termId)
    {
        TermlyTeacherEffectiveIndex::where([
            'academic_session_id' => $sessionId,
            'section_id' => $sectionId,
            'term_id' => $termId
        ])->delete();

        TeachersClassSubjectComparison::where([
            'academic_session_id' => $sessionId,
            'section_id' => $sectionId,
            'term_id' => $termId
        ])->delete();

        TermlySubjectEvaluation::where([
            'academic_session_id' => $sessionId,
            'section_id' => $sectionId,
            'term_id' => $termId
        ])->delete();

        TermlyClassAveraging::where([
            'academic_session_id' => $sessionId,
            'section_id' => $sectionId,
            'term_id' => $termId
        ])->delete();
        // Central and Dispersion Measures
        CentralAndDisperseResultMeasure::where([
            'academic_session_id' => $sessionId,
            'term_id' => $termId,
            'section_id' => $sectionId
        ])->delete();
    }

    // --------------------------------------------------
    // 1️⃣ Teacher Effectiveness Index
    // --------------------------------------------------
    private function computeTeacherEffectiveness($sectionId, $sessionId, $termId)
    {
        $maxScore = 100;
        $section = Section::find($sectionId);

        foreach (Teacher::all() as $teacher) {

           

                $totalObtained = 0;
                $totalPossible = 0;

                $students = collect();
                $subjects = collect();
                $classes  = collect();

                $allocations = $section
                    ->teacherAllocations($teacher->id);

                foreach ($allocations as $assignment) {

                    $results = $assignment
                        ->getStudentSessionResultsForTerm($sessionId, $termId);

                    if ($results->isEmpty()) {
                        continue;
                    }

                    $studentsCount = $results
                        ->pluck('sectionClassStudentTerm.section_class_student_id')
                        ->unique()
                        ->count();

                    $totalObtained += $results->sum('total');
                    $totalPossible += $studentsCount * $maxScore;

                    $students = $students->merge(
                        $results->pluck('sectionClassStudentTerm.section_class_student_id')
                    );

                    $subjects->push($assignment->sectionClassSubject->subject_id);
                    $classes->push($assignment->sectionClassSubject->section_class_id);
                }

                if ($totalPossible === 0) {
                    continue;
                }

                TermlyTeacherEffectiveIndex::updateOrCreate(
                    [
                        'academic_session_id' => $sessionId,
                        'term_id'             => $termId,
                        'section_id'          => $section->id,
                        'teacher_id'          => $teacher->id,
                    ],
                    [
                        'total_students'      => $students->unique()->count(),
                        'total_subjects'      => $subjects->unique()->count(),
                        'total_classes'       => $classes->unique()->count(),
                        'total_obtained'      => $totalObtained,
                        'total_possible'      => $totalPossible,
                        'effectiveness_index' => round(
                            ($totalObtained / $totalPossible) * 100,
                            2
                        ),
                    ]
                );
            
        }
    }


    // --------------------------------------------------
    // 2️⃣ Teacher × Class × Subject Comparison
    // --------------------------------------------------
    private function computeTeacherClassSubjectComparison($sectionId, $sessionId, $termId)
    {
        $maxScore = 100;
        $section = Section::find($sectionId);

        $assignments = $section->allTeachersAllocationInSection();
        

        foreach ($assignments as $assignment) {

            // 🔹 Pull ONLY real results for this teacher+subject+class+term+session
            $results = $assignment
                ->getStudentSessionResultsForTerm($sessionId, $termId);

            if ($results->isEmpty()) {
                continue;
            }

            // 🔹 Students who ACTUALLY have results
            $studentsCount = $results
                ->pluck('sectionClassStudentTerm.section_class_student_id')
                ->unique()
                ->count();

            if ($studentsCount === 0) {
                continue;
            }

            $totalObtained = $results->sum('total');
            $totalPossible = $studentsCount * $maxScore;

            TeachersClassSubjectComparison::updateOrCreate(
                [
                    // 🔑 Uniqueness definition
                    'academic_session_id' => $sessionId,
                    'term_id'             => $termId,
                    'teacher_id'          => $assignment->teacher_id,
                    'section_class_id'    => $assignment->sectionClassSubject->section_class_id,
                    'subject_id'          => $assignment->sectionClassSubject->subject_id,
                    'section_id'     => $sectionId,
                ],
                [
                    // 📍 Section is now FIRST-CLASS
                    'students_count' => $studentsCount,
                    'total_obtained' => $totalObtained,
                    'total_possible' => $totalPossible,
                    'percentage'     => round(($totalObtained / $totalPossible) * 100, 2),
                ]
            );
        }
    }


    // --------------------------------------------------
    // 4️⃣ Termly Class Averaging
    // --------------------------------------------------
    private function computeClassAveraging($sectionId, $sessionId, $termId)
    {
        $section = Section::find($sectionId);

        foreach ($section->sectionClasses as $class) {

            $results = $class->getStudentSessionResultsForTerm($sessionId, $termId);

            if ($results->isEmpty()) continue;

            $studentsCount = $class->sectionClassStudents->where('status', 'Active')->count();
            $subjectsCount = $class->sectionClassSubjects->where('status', 'Active')->count();
            $totalObtained = round(($results->sum('total') / $subjectsCount), 2);
            $totalPossible = $subjectsCount * 100;

            TermlyClassAveraging::create([
                'section_id' => $sectionId,
                'academic_session_id' => $sessionId,
                'section_class_id' => $class->id,
                'term_id' => $termId,
                'students_count' => $studentsCount,
                'subjects_count' => $subjectsCount,
                'total_obtained' => $totalObtained,
                'total_possible' => $totalPossible,
                'class_average' => round(($totalObtained / $totalPossible) * 100, 2),
            ]);
        }
    }
    // --------------------------------------------------
    // 3️⃣ Termly Subject Evaluation
    // --------------------------------------------------
    private function computeSubjectEvaluation($sectionId, $sessionId, $termId)
    {
        $maxScore = 100;
        $section = Section::find($sectionId);
        $classSubjects = [];

        // Load all class-subject combinations for the section
        foreach ($section->sectionClasses as $sectionClass) {
            foreach ($sectionClass->sectionClassSubjects->where('status','Active') as $sectionClassSubject) {
                $classSubjects[] = $sectionClassSubject;
            }
        }
        
        

        foreach ($classSubjects as $scs) {

            // 🔹 Pull only real results for this subject+class+term+session
            $results = $scs->getStudentSessionResultsForTerm($sessionId, $termId);

            if ($results->isEmpty()) {
                continue;
            }

            // 🔹 Count students who actually have results
            $studentsCount = $results
                ->pluck('sectionClassStudentTerm.section_class_student_id')
                ->unique()
                ->count();

            if ($studentsCount === 0) {
                continue;
            }

            $totalObtained = $results->sum('total');
            $totalPossible = $studentsCount * $maxScore;

            TermlySubjectEvaluation::updateOrCreate(
                [
                    // 🔑 Uniqueness
                    'academic_session_id' => $sessionId,
                    'term_id'             => $termId,
                    'subject_id'          => $scs->subject_id,
                    'section_class_id'    => $scs->section_class_id,
                    'section_id'      => $sectionId
                ],
                [
                    // 📍 Section awareness
                    'students_count'  => $studentsCount,
                    'total_obtained'  => $totalObtained,
                    'total_possible'  => $totalPossible,
                    'average'         => round(
                        ($totalObtained / $totalPossible) * 100,
                        2
                    ),
                ]
            );
        }
    }

    private function computeCentralAndDispersionMeasures($sectionId, $sessionId, $termId)
    {
        $section = Section::find($sectionId);

        $assignments = $section->allTeachersAllocationInSection();

        $sessionTermId = DB::table('academic_session_terms')
            ->where('academic_session_id', $sessionId)
            ->where('term_id', $termId)
            ->value('id');

        foreach ($assignments as $assignment) {
            
            // 1️⃣ Get all uploads for this session & term
            $uploads = $assignment->subjectTeacherTermlyUploads()->where('academic_session_id', $sessionId)
                ->where('term_id', $termId)
                ->with([
                    'sectionClassSubjectTeacher',
                    'studentResults'
                ])
                ->get();

            foreach ($uploads as $upload) {

                $results = $upload->studentResults;

                // Skip empty uploads
                if ($results->isEmpty()) {
                    continue;
                }

                // 2️⃣ Extract raw scores
                $scores = $results->pluck('total')->sort()->values();

                $studentsCount = $scores->count();

                // Safety check
                if ($studentsCount === 0) {
                    continue;
                }

                // 3️⃣ Central Tendency
                $mean = round($scores->avg(), 2);
                $median = round($this->calculateMedian($scores), 2);
                $mode = $this->calculateMode($scores);

                // 4️⃣ Dispersion
                $min = $scores->min();
                $max = $scores->max();
                $range = $max - $min;

                $variance = $this->calculateVariance($scores, $mean);
                $stdDev = $variance !== null ? sqrt($variance) : null;

                // 5️⃣ Persist (safe re-run)
                CentralAndDisperseResultMeasure::updateOrCreate(
                    [
                        'subject_teacher_termly_upload_id' => $upload->id,
                    ],
                    [
                        'academic_session_id' => $sessionId,
                        'term_id' => $termId,
                        'section_id' => $upload->sectionClassSubjectTeacher->sectionClassSubject->sectionClass->section_id,
                        'section_class_id' => $upload->sectionClassSubjectTeacher->sectionClassSubject->section_class_id,
                        'students_count' => $studentsCount,

                        // Central tendency
                        'mean' => $mean,
                        'median' => $median,
                        'mode' => $mode,

                        // Dispersion
                        'min_score' => $min,
                        'max_score' => $max,
                        'range' => $range,
                        'variance' => $variance,
                        'standard_deviation' => $stdDev,
                    ]
                );
            }
        }
    }
    

    private function calculateMedian(Collection $values): float
    {
        $count = $values->count();
        $middle = intdiv($count, 2);

        if ($count % 2) {
            return $values[$middle];
        }

        return ($values[$middle - 1] + $values[$middle]) / 2;
    }

    private function calculateMode(Collection $values): ?float
    {
        $frequency = $values->countBy();
        $maxCount = $frequency->max();

        // No mode if all values appear once
        if ($maxCount <= 1) {
            return null;
        }

        return (float) $frequency
            ->filter(fn ($count) => $count === $maxCount)
            ->keys()
            ->first();
    }

    private function calculateVariance(Collection $values, float $mean): ?float
    {
        $count = $values->count();

        if ($count === 0) {
            return null;
        }

        $sumSquaredDiffs = $values->reduce(
            fn ($carry, $value) => $carry + pow($value - $mean, 2),
            0
        );

        return round($sumSquaredDiffs / $count, 4);
    }


}
