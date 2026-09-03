<?php

namespace App\Http\Controllers\Examination;

use App\Http\Controllers\Controller;
use App\Models\AcademicSession;
use App\Models\AffectiveTrait;
use App\Models\GradeScale;
use App\Models\Psychomotor;
use App\Models\RemarkScale;
use App\Models\Section;
use App\Models\SectionClassStudent;
use App\Models\SectionClassStudentTerm;
use App\Models\Term;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StudentResultController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->validate([
            'session' => ['nullable', 'integer', 'exists:academic_sessions,id'],
            'term' => ['nullable', 'integer', 'exists:terms,id'],
            'section' => ['nullable', 'integer', 'exists:sections,id'],
            'class' => [
                'nullable',
                'integer',
                Rule::exists('section_classes', 'id')->where(function ($query) use ($request) {
                    if ($request->filled('section')) {
                        $query->where('section_id', $request->input('section'));
                    }
                }),
            ],
            'search' => ['nullable', 'string', 'max:100'],
        ]);

        $students = null;
        if ($request->filled(['session', 'term', 'section', 'class'])) {
            $search = trim($filters['search'] ?? '');
            $students = SectionClassStudent::query()
                ->with([
                    'student.guardian',
                    'sectionClass.section',
                    'sectionClassStudentTerms' => function ($query) use ($filters) {
                        $query->whereHas('academicSessionTerm', function ($query) use ($filters) {
                            $query->where('academic_session_id', $filters['session'])
                                ->where('term_id', $filters['term']);
                        })->with(['academicSessionTerm.term', 'studentResults']);
                    },
                ])
                ->where('section_class_students.academic_session_id', $filters['session'])
                ->where('section_class_students.section_class_id', $filters['class'])
                ->when($search !== '', function ($query) use ($search) {
                    $query->whereHas('student', function ($query) use ($search) {
                        $query->where(function ($query) use ($search) {
                            $query->where('name', 'like', "%{$search}%")
                                ->orWhere('admission_no', 'like', "%{$search}%")
                                ->orWhereHas('guardian', function ($query) use ($search) {
                                    $query->where('name', 'like', "%{$search}%")
                                        ->orWhere('phone', 'like', "%{$search}%")
                                        ->orWhere('email', 'like', "%{$search}%");
                                });
                        });
                    });
                })
                ->join('students', 'students.id', '=', 'section_class_students.student_id')
                ->select('section_class_students.*')
                ->orderBy('students.name')
                ->paginate(30)
                ->withQueryString();
        }

        return view('exam.student-results.index', [
            'sessions' => AcademicSession::orderByDesc('id')->get(),
            'terms' => Term::orderBy('id')->get(),
            'sections' => Section::with(['sectionClasses' => fn ($query) => $query->orderBy('name')])->orderBy('name')->get(),
            'students' => $students,
        ]);
    }

    public function download(SectionClassStudentTerm $studentTerm)
    {
        $studentTerm->load([
            'academicSessionTerm.academicSession',
            'academicSessionTerm.term',
            'sectionClassStudent.student.gender',
            'sectionClassStudent.sectionClass.sectionClassStudents',
            'sectionClassStudent.sectionClass.sectionClassSubjects',
            'studentResults.subjectTeacherTermlyUpload.sectionClassSubjectTeacher.sectionClassSubject',
            'sectionClassStudentTermResultPublish',
            'sectionClassStudentTermAccessment',
        ]);

        $sectionClassStudent = $studentTerm->sectionClassStudent;
        abort_unless($sectionClassStudent, 404);

        $data = [
            'sectionClassStudentTerm' => $studentTerm,
            'sectionClassStudent' => $sectionClassStudent,
            'student' => $sectionClassStudent->student,
            'gradeScales' => GradeScale::all(),
            'remarkScales' => RemarkScale::all(),
            'psychomotors' => Psychomotor::all(),
            'affectiveTraits' => AffectiveTrait::all(),
        ];

        $html = view('exam.student-results.report-card-pdf', $data)->render();
        $pdf = new \Dompdf\Dompdf(['isRemoteEnabled' => true]);
        $pdf->loadHtml($html);
        $pdf->setPaper('a4', 'portrait');
        $pdf->render();

        $name = preg_replace('/[^A-Za-z0-9_-]+/', '-', $sectionClassStudent->student->name);
        $session = str_replace('/', '-', $studentTerm->academicSessionTerm->academicSession->name);
        $filename = trim($name, '-').'-'.$session.'-'.$studentTerm->academicSessionTerm->term->name.'-report-card.pdf';

        return response($pdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ]);
    }
}
