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
            $totalObtained = $results->sum('total');
            $totalPossible = $studentsCount * $subjectsCount * 100;

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

}
