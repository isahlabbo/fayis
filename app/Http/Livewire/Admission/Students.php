<?php

namespace App\Http\Livewire\Admission;

use App\Models\AcademicSession;
use App\Models\Section;
use App\Models\SectionClass;
use App\Models\SectionClassStudent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class Students extends Component
{
    public $search = '', $sectionId = '', $classId = '', $sessionId = '', $status = 'Active';
    public $selected = [], $selectAll = false;
    public $targetSessionId = '', $targetStatus = '';

    public function deleteSelectedEnrolments()
    {
        abort_unless(Auth::check() && Auth::user()->hasPermission('manage-admissions'), 403);
        $data = $this->validate([
            'sessionId' => 'required|integer|exists:academic_sessions,id',
            'classId' => 'required|integer|exists:section_classes,id',
            'selected' => 'required|array|min:1',
            'selected.*' => 'required|integer|distinct',
        ], [
            'sessionId.required' => 'Filter by a specific academic session before deleting enrolments.',
            'classId.required' => 'Filter by a specific class before deleting enrolments.',
        ]);

        DB::transaction(function () use ($data) {
            $enrolments = SectionClassStudent::whereIn('id', $data['selected'])
                ->where('academic_session_id', $data['sessionId'])->where('section_class_id', $data['classId'])
                ->orderBy('id')->lockForUpdate()->get();
            if ($enrolments->count() !== count($data['selected']) || $enrolments->pluck('id')->diff($this->records()->pluck('id'))->isNotEmpty()) {
                throw ValidationException::withMessages(['selected' => 'The selection no longer matches this class, session and displayed list. Select the enrolments again.']);
            }
            $ids = $enrolments->pluck('id');
            $termIds = DB::table('section_class_student_terms')->whereIn('section_class_student_id', $ids)
                ->lockForUpdate()->pluck('id');

            // Refuse deletion rather than cascade or orphan records outside the two requested tables.
            $references = [
                ['payments', 'section_class_student_id', $ids],
                ['section_class_student_payments', 'section_class_student_id', $ids],
                ['section_class_student_repeatings', 'section_class_student_id', $ids],
                ['inventory_sales', 'section_class_student_id', $ids],
                ['inventory_usages', 'section_class_student_id', $ids],
                ['material_collections', 'section_class_student_id', $ids],
                ['student_promotions', 'from_enrolment_id', $ids],
                ['student_promotions', 'to_enrolment_id', $ids],
                ['student_results', 'section_class_student_term_id', $termIds],
                ['section_class_student_term_accessments', 'section_class_student_term_id', $termIds],
                ['section_class_student_term_result_publishes', 'section_class_student_term_id', $termIds],
                ['invoices', 'section_class_student_term_id', $termIds],
            ];
            foreach ($references as [$table, $column, $values]) {
                if ($values->isNotEmpty() && Schema::hasTable($table) && DB::table($table)->whereIn($column, $values)->lockForUpdate()->first()) {
                    throw ValidationException::withMessages(['selected' => 'Nothing was deleted. A selected enrolment has linked records in '.$table.'. Review those records before deleting the enrolment.']);
                }
            }
            DB::table('section_class_student_terms')->whereIn('id', $termIds)->delete();
            DB::table('section_class_students')->whereIn('id', $ids)->delete();
        });
        $count = count($data['selected']);
        $this->clearSelection();
        $this->reset(['targetSessionId', 'targetStatus']);
        session()->flash('success', $count.' selected enrolment(s) and their term rows deleted. Student profiles and other class/session enrolments were preserved.');
    }

    public function updateSelected()
    {
        $data = $this->validate([
            'selected' => 'required|array|min:1',
            'selected.*' => 'required|integer|distinct',
            'targetSessionId' => 'nullable|integer|exists:academic_sessions,id',
            'targetStatus' => ['nullable', Rule::in($this->statuses()->all())],
        ]);
        if (!$this->targetSessionId && !$this->targetStatus) {
            $this->addError('targetStatus', 'Choose a session or status to update.');
            return;
        }

        DB::transaction(function () use ($data) {
            $sources = SectionClassStudent::whereIn('id', $data['selected'])->orderBy('id')->lockForUpdate()->get();
            $visibleIds = $this->records()->pluck('id');
            if ($sources->count() !== count($data['selected']) || $sources->pluck('id')->diff($visibleIds)->isNotEmpty()) {
                throw ValidationException::withMessages(['selected' => 'The selected students no longer match the displayed list. Refresh your selection.']);
            }
            $session = $this->targetSessionId ? AcademicSession::with('academicSessionTerms')->findOrFail($this->targetSessionId) : null;
            if ($session && $session->academicSessionTerms->isEmpty()) {
                throw ValidationException::withMessages(['targetSessionId' => 'Configure terms for the selected session first.']);
            }
            foreach ($sources as $source) {
                $status = $this->targetStatus ?: $source->status;
                $target = $source;
                if ($session && (int) $source->academic_session_id !== (int) $session->id) {
                    // Retain the old enrolment so payments and results stay in their original session.
                    $source->update(['status' => 'Not Active']);
                    $source->sectionClassStudentTerms()->update(['status' => 'Not Active']);
                    $target = SectionClassStudent::firstOrCreate([
                        'student_id' => $source->student_id, 'section_class_id' => $source->section_class_id,
                        'academic_session_id' => $session->id,
                    ], ['status' => $status]);
                }
                $target->update(['status' => $status]);
                $terms = $session ? $session->academicSessionTerms : $target->academicSession->academicSessionTerms;
                foreach ($terms as $term) {
                    $target->sectionClassStudentTerms()->updateOrCreate(
                        ['academic_session_term_id' => $term->id],
                        ['status' => $status === 'Active' && $term->status === 'Active' ? 'Active' : 'Not Active']
                    );
                }
            }
        });
        session()->flash('success', count($data['selected']).' student(s) updated successfully.');
        if ($this->targetSessionId) $this->sessionId = (string) $this->targetSessionId;
        if ($this->targetStatus) $this->status = $this->targetStatus;
        $this->reset(['targetSessionId', 'targetStatus']);
        $this->clearSelection();
    }

    public function boot()
    {
        abort_unless(Auth::check() && Auth::user()->hasPermission('manage-admissions'), 403);
    }

    public function mount()
    {
        $this->sessionId = (string) (AcademicSession::where('status', 'Active')->latest('id')->value('id') ?? '');
    }

    public function updated($property)
    {
        if (in_array($property, ['search', 'sectionId', 'classId', 'sessionId', 'status'], true)) {
            if ($property === 'sectionId') $this->classId = '';
            $this->clearSelection();
        }
    }

    public function clearSelection()
    {
        $this->selected = [];
        $this->selectAll = false;
        $this->resetValidation();
    }

    public function updatedSelectAll($checked)
    {
        $this->selected = $checked ? $this->records()->pluck('id')->map(fn ($id) => (string) $id)->all() : [];
    }

    public function updatedSelected()
    {
        $ids = $this->records()->pluck('id')->map(fn ($id) => (string) $id);
        $this->selected = collect($this->selected)->map(fn ($id) => (string) $id)->intersect($ids)->unique()->values()->all();
        $this->selectAll = $ids->isNotEmpty() && count($this->selected) === $ids->count();
    }

    private function records()
    {
        return SectionClassStudent::with(['student.guardian', 'student.gender', 'academicSession'])
            ->whereHas('student')
            ->when($this->sessionId, fn ($q) => $q->where('academic_session_id', $this->sessionId))
            ->when($this->status, fn ($q) => $q->where('status', $this->status))
            ->when($this->sectionId, fn ($q) => $q->whereHas('sectionClass', fn ($q) => $q->where('section_id', $this->sectionId)))
            ->when($this->classId, fn ($q) => $q->where('section_class_id', $this->classId))
            ->when($this->search, fn ($q) => $q->whereHas('student', fn ($q) => $q->where('name', 'like', '%'.$this->search.'%')->orWhere('admission_no', 'like', '%'.$this->search.'%')))
            ->orderByDesc('academic_session_id')->orderByDesc('id')->get()->unique('student_id')->values();
    }

    public function render()
    {
        $records = $this->records();
        $statistics = [
            'total' => $records->count(),
            'male' => $records->filter(fn ($record) => strtolower(trim(optional($record->student->gender)->name ?? '')) === 'male')->count(),
            'female' => $records->filter(fn ($record) => strtolower(trim(optional($record->student->gender)->name ?? '')) === 'female')->count(),
        ];
        $statistics['unspecified'] = $statistics['total'] - $statistics['male'] - $statistics['female'];

        return view('livewire.admission.students', compact('records', 'statistics') + [
            'sections' => Section::orderBy('name')->get(),
            'classes' => SectionClass::when($this->sectionId, fn ($q) => $q->where('section_id', $this->sectionId))->orderBy('name')->get(),
            'sessions' => AcademicSession::orderByDesc('id')->get(),
            'statuses' => $this->statuses(),
        ]);
    }

    private function statuses()
    {
        return collect(['Active', 'Not Active'])->merge(SectionClassStudent::distinct()->pluck('status'))->filter()->unique()->values();
    }
}
