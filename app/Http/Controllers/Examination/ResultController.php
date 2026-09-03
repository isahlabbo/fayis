<?php

namespace App\Http\Controllers\Examination;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Section;
use App\Models\SectionClass;
use App\Models\StudentResult;
use App\Models\AcademicSession;
use App\Models\SectionClassStudentTerm;
use App\Models\Term;
use Illuminate\Validation\Rule;

class ResultController extends Controller
{
    public function update(Request $request, $studentResultId) {
        $request->validate([
            'first_ca'=>'required|numeric|min:0|max:20',
            'second_ca'=>'required|numeric|min:0|max:20',
            'exam'=>'required|numeric|min:0|max:60',
        ]);

        $result = StudentResult::find($studentResultId);
        $result->first_ca = $request->first_ca;
        $result->second_ca = $request->second_ca;
        $result->exam = $request->exam;
        $result->save();
        $result->updateTotalAndComputeGrade();

        return redirect()->route('exam.upload.details',[$result->subjectTeacherTermlyUpload->id])->withSuccess('Result Updated Successfully');
    }

    public function publish($sectionClassId) {
        $sectionClass = SectionClass::find($sectionClassId);
        foreach($sectionClass->sectionClassStudents->where('status', 'Active') as $studentInClass){
            foreach($studentInClass->sectionClassStudentTerms->where('academic_session_term_id', $sectionClass->currentSessionTerm()->id) as $studentTerm){
                $publish = $studentTerm->sectionClassStudentTermResultPublish()->firstOrCreate();
                $publish->updatePublishRecord();
                $studentTerm->publishUpload();
            }
        }


        return redirect()->route('exam.upload.summary',[$sectionClassId])->withSuccess('Results Published Successfully');
    }

    public function accessCode($sectionId) {
        $section = Section::find($sectionId);
        return view('exam.upload.result.accessCode', ['section'=>$section]);
    }

    public function accessCodes(Request $request)
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

        $studentTerms = null;
        $missingCodeCount = 0;
        if ($request->filled(['session', 'term', 'section', 'class'])) {
            $query = $this->accessCodeQuery($filters);
            $missingCodeCount = (clone $query)
                ->where(function ($query) {
                    $query->whereNull('section_class_student_terms.access_code')
                        ->orWhere('section_class_student_terms.access_code', '');
                })
                ->count('section_class_student_terms.id');

            $studentTerms = $query
                ->paginate(30)
                ->withQueryString();
        }

        return view('exam.result-access-codes.index', [
            'sessions' => AcademicSession::orderByDesc('id')->get(),
            'terms' => Term::orderBy('id')->get(),
            'sections' => Section::with(['sectionClasses' => fn ($query) => $query->orderBy('name')])->orderBy('name')->get(),
            'studentTerms' => $studentTerms,
            'missingCodeCount' => $missingCodeCount,
        ]);
    }

    public function generateAccessCodes(Request $request)
    {
        $filters = $request->validate([
            'session' => ['required', 'integer', 'exists:academic_sessions,id'],
            'term' => ['required', 'integer', 'exists:terms,id'],
            'section' => ['required', 'integer', 'exists:sections,id'],
            'class' => [
                'required',
                'integer',
                Rule::exists('section_classes', 'id')->where('section_id', $request->input('section')),
            ],
            'search' => ['nullable', 'string', 'max:100'],
        ]);

        $ids = $this->accessCodeQuery($filters)
            ->where(function ($query) {
                $query->whereNull('section_class_student_terms.access_code')
                    ->orWhere('section_class_student_terms.access_code', '');
            })
            ->pluck('section_class_student_terms.id');

        $ids->each(function ($id) {
            SectionClassStudentTerm::findOrFail($id)->generateResultAccessCode();
        });

        return redirect()->route('exam.result-access-codes.index', $request->only('session', 'term', 'section', 'class', 'search'))
            ->withSuccess($ids->count().' result access '.\Illuminate\Support\Str::plural('code', $ids->count()).' generated successfully.');
    }

    private function accessCodeQuery(array $filters)
    {
        $search = trim($filters['search'] ?? '');

        return SectionClassStudentTerm::query()
            ->with([
                'academicSessionTerm.academicSession',
                'academicSessionTerm.term',
                'sectionClassStudent.student.guardian',
                'sectionClassStudent.sectionClass',
            ])
            ->whereHas('academicSessionTerm', function ($query) use ($filters) {
                $query->where('academic_session_id', $filters['session'])
                    ->where('term_id', $filters['term']);
            })
            ->whereHas('sectionClassStudent', function ($query) use ($filters) {
                $query->where('section_class_id', $filters['class'])
                    ->where('academic_session_id', $filters['session'])
                    ->whereHas('sectionClass', fn ($query) => $query->where('section_id', $filters['section']));
            })
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('section_class_student_terms.access_code', 'like', "%{$search}%")
                        ->orWhereHas('sectionClassStudent.student', function ($query) use ($search) {
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
                });
            })
            ->join('section_class_students', 'section_class_students.id', '=', 'section_class_student_terms.section_class_student_id')
            ->join('students', 'students.id', '=', 'section_class_students.student_id')
            ->select('section_class_student_terms.*')
            ->orderBy('students.name');
    }
}
