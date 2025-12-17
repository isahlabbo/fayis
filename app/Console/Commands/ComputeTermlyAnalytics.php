<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

use App\Models\Teacher;
use App\Models\SectionClass;
use App\Models\Subject;
use App\Models\SectionClassSubjectTeacher;
use App\Models\StudentResult;

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

        $this->info("Starting analytics computation for session {$sessionId}, term {$termId}...");

        DB::transaction(function () use ($sessionId, $termId) {

            $this->clearOldAnalytics($sessionId, $termId);

            $this->computeTeacherEffectiveness($sessionId, $termId);
            $this->computeTeacherClassSubjectComparison($sessionId, $termId);
            $this->computeSubjectEvaluation($sessionId, $termId);
            $this->computeClassAveraging($sessionId, $termId);
        });

        $this->info('✅ Analytics computation completed successfully!');
    }

    // --------------------------------------------------
    // 🔥 Clear old analytics for the session+term
    // --------------------------------------------------
    private function clearOldAnalytics($sessionId, $termId)
    {
        TermlyTeacherEffectiveIndex::where([
            'academic_session_id' => $sessionId,
            'term_id' => $termId
        ])->delete();

        TeachersClassSubjectComparison::where([
            'academic_session_id' => $sessionId,
            'term_id' => $termId
        ])->delete();

        TermlySubjectEvaluation::where([
            'academic_session_id' => $sessionId,
            'term_id' => $termId
        ])->delete();

        TermlyClassAveraging::where([
            'academic_session_id' => $sessionId,
            'term_id' => $termId
        ])->delete();
    }

    // --------------------------------------------------
    // 1️⃣ Teacher Effectiveness Index
    // --------------------------------------------------
    private function computeTeacherEffectiveness($sessionId, $termId)
    {
        foreach (Teacher::all() as $teacher) {

            $assignments = SectionClassSubjectTeacher::where('teacher_id', $teacher->id)
                ->where('status', 'Active')
                ->get();

            $totalObtained = 0;
            $totalPossible = 0;
            $students = collect();
            $subjects = collect();
            $classes = collect();

            foreach ($assignments as $assignment) {

                $results = StudentResult::whereHas(
                    'sectionClassStudentTerm.academicSessionTerm',
                    function ($q) use ($sessionId, $termId) {
                        $q->where('academic_session_id', $sessionId)
                          ->where('term_id', $termId);
                    }
                )->whereHas(
                    'subjectTeacherTermlyUpload',
                    function ($q) use ($assignment) {
                        $q->where('section_class_subject_teacher_id', $assignment->id);
                    }
                )->get();

                if ($results->isEmpty()) continue;

                $totalObtained += $results->sum('total');
                $totalPossible += $results->count() * 100;

                $students = $students->merge($results->pluck('sectionClassStudentTerm.section_class_student_id'));
                $subjects->push($assignment->sectionClassSubject->subject_id);
                $classes->push($assignment->sectionClassSubject->section_class_id);
            }

            if ($totalPossible === 0) continue;

            TermlyTeacherEffectiveIndex::create([
                'academic_session_id' => $sessionId,
                'term_id' => $termId,
                'teacher_id' => $teacher->id,
                'total_students' => $students->unique()->count(),
                'total_subjects' => $subjects->unique()->count(),
                'total_classes' => $classes->unique()->count(),
                'total_obtained' => $totalObtained,
                'total_possible' => $totalPossible,
                'effectiveness_index' => round(($totalObtained / $totalPossible) * 100, 2),
            ]);
        }
    }

    // --------------------------------------------------
    // 2️⃣ Teacher × Class × Subject Comparison
    // --------------------------------------------------
    private function computeTeacherClassSubjectComparison($sessionId, $termId)
    {
        $assignments = SectionClassSubjectTeacher::where('status', 'Active')->get();

        foreach ($assignments as $assignment) {

            $results = StudentResult::whereHas(
                'sectionClassStudentTerm.academicSessionTerm',
                function ($q) use ($sessionId, $termId) {
                    $q->where('academic_session_id', $sessionId)
                      ->where('term_id', $termId);
                }
            )->whereHas(
                'subjectTeacherTermlyUpload',
                function ($q) use ($assignment) {
                    $q->where('section_class_subject_teacher_id', $assignment->id);
                }
            )->get();

            if ($results->isEmpty()) continue;

            $studentsCount = $results->count();
            $totalObtained = $results->sum('total');
            $totalPossible = $studentsCount * 100;

            TeachersClassSubjectComparison::create([
                'academic_session_id' => $sessionId,
                'teacher_id' => $assignment->teacher_id,
                'section_class_id' => $assignment->sectionClassSubject->section_class_id,
                'subject_id' => $assignment->sectionClassSubject->subject_id,
                'term_id' => $termId,
                'students_count' => $studentsCount,
                'total_obtained' => $totalObtained,
                'total_possible' => $totalPossible,
                'percentage' => round(($totalObtained / $totalPossible) * 100, 2),
            ]);
        }
    }

    // --------------------------------------------------
    // 3️⃣ Termly Subject Evaluation
    // --------------------------------------------------
    private function computeSubjectEvaluation($sessionId, $termId)
    {
        $classes = SectionClass::with('sectionClassSubjects')->get();

        foreach ($classes as $class) {
            foreach ($class->sectionClassSubjects as $scs) {

                $results = StudentResult::whereHas(
                    'sectionClassStudentTerm.academicSessionTerm',
                    function ($q) use ($sessionId, $termId) {
                        $q->where('academic_session_id', $sessionId)
                          ->where('term_id', $termId);
                    }
                )->whereHas(
                    'subjectTeacherTermlyUpload',
                    function ($q) use ($scs) {
                        $q->where('section_class_subject_id', $scs->id);
                    }
                )->get();

                if ($results->isEmpty()) continue;

                $studentsCount = $results->count();
                $totalObtained = $results->sum('total');
                $totalPossible = $studentsCount * 100;

                TermlySubjectEvaluation::create([
                    'academic_session_id' => $sessionId,
                    'subject_id' => $scs->subject_id,
                    'section_class_id' => $class->id,
                    'term_id' => $termId,
                    'students_count' => $studentsCount,
                    'total_obtained' => $totalObtained,
                    'total_possible' => $totalPossible,
                    'average' => round(($totalObtained / $totalPossible) * 100, 2),
                ]);
            }
        }
    }

    // --------------------------------------------------
    // 4️⃣ Termly Class Averaging
    // --------------------------------------------------
    private function computeClassAveraging($sessionId, $termId)
    {
        foreach (SectionClass::all() as $class) {

            $results = StudentResult::whereHas(
                'sectionClassStudentTerm.academicSessionTerm',
                function ($q) use ($sessionId, $termId) {
                    $q->where('academic_session_id', $sessionId)
                      ->where('term_id', $termId);
                }
            )->get();

            if ($results->isEmpty()) continue;

            $studentsCount = $results->pluck('sectionClassStudentTerm.section_class_student_id')->unique()->count();
            $subjectsCount = $results->pluck('subject_id')->unique()->count();
            $totalObtained = $results->sum('total');
            $totalPossible = $studentsCount * $subjectsCount * 100;

            TermlyClassAveraging::create([
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
}
