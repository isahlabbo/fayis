<?php

namespace App\Http\Livewire\Admission;

use App\Models\AcademicSession;
use App\Models\Section;
use App\Models\SectionClass;
use App\Models\SectionClassStudent;
use App\Models\StudentPromotion;
use App\Services\Finance\ApplyAdvancePayments;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class Promotions extends Component
{
    public $mode = 'promote';
    public $fromSectionId = '', $fromClassId = '';
    public $toSectionId = '', $toClassId = '';
    public $selected = [], $selectAll = false;

    public function boot()
    {
        abort_unless(Auth::check() && Auth::user()->hasAnyPermission('manage-admissions', 'manage-students'), 403);
    }

    public function updatedMode() { $this->clearSelection(); }
    public function updatedFromSectionId() { $this->fromClassId = ''; $this->clearSelection(); }
    public function updatedToSectionId() { $this->toClassId = ''; $this->clearSelection(); }
    public function updatedFromClassId() { $this->clearSelection(); }
    public function updatedToClassId() { $this->clearSelection(); }

    public function updatedSelectAll($checked)
    {
        $this->selected = $checked ? $this->records()->pluck('id')->map(fn ($id) => (string) $id)->all() : [];
    }

    public function savePromotion()
    {
        $this->validateSelection();
        $sourceIds = $this->records()->whereIn('id', $this->selected)->pluck('id');
        if ($sourceIds->count() !== count($this->selected)) throw ValidationException::withMessages(['selected' => 'One or more selected students are no longer available.']);

        $targetSession = $this->nextSession();
        DB::transaction(function () use ($sourceIds, $targetSession) {
            $sources = SectionClassStudent::whereIn('id', $sourceIds)->lockForUpdate()->get();
            foreach ($sources as $source) {
                $source->sectionClassStudentTerms()->update(['status' => 'Not Active']);
                $source->update(['status' => 'Not Active']);
                $target = SectionClassStudent::firstOrCreate([
                    'student_id' => $source->student_id,
                    'section_class_id' => $this->toClassId,
                    'academic_session_id' => $targetSession->id,
                ], ['status' => 'Active']);
                $target->update(['status' => 'Active']);
                foreach ($targetSession->academicSessionTerms as $sessionTerm) {
                    $target->sectionClassStudentTerms()->updateOrCreate(
                        ['academic_session_term_id' => $sessionTerm->id],
                        ['status' => $sessionTerm->term_id == 1 ? 'Active' : 'Not Active']
                    );
                }
                // A target enrolment may already exist after a cancelled promotion, so
                // do not rely only on the model's "created" listener to apply advances.
                app(ApplyAdvancePayments::class)->handle($target->fresh(['student', 'sectionClass']));
                StudentPromotion::updateOrCreate([
                    'from_enrolment_id' => $source->id,
                    'to_enrolment_id' => $target->id,
                ], [
                    'student_id' => $source->student_id,
                    'promoted_by' => Auth::id(),
                    'cancelled_at' => null,
                    'cancelled_by' => null,
                ]);
            }
        });
        session()->flash('success', count($this->selected).' student(s) promoted successfully.');
        $this->clearSelection();
    }

    public function cancelPromotion()
    {
        $this->validateSelection();
        $promotionIds = $this->records()->whereIn('id', $this->selected)->pluck('id');
        if ($promotionIds->count() !== count($this->selected)) throw ValidationException::withMessages(['selected' => 'One or more selected promotions are no longer available.']);

        DB::transaction(function () use ($promotionIds) {
            $promotions = StudentPromotion::with(['fromEnrolment', 'toEnrolment'])->whereIn('id', $promotionIds)->lockForUpdate()->get();
            foreach ($promotions as $promotion) {
                $promotion->toEnrolment->sectionClassStudentTerms()->update(['status' => 'Not Active']);
                $promotion->toEnrolment->update(['status' => 'Not Active']);
                $promotion->fromEnrolment->update(['status' => 'Active']);
                $promotion->fromEnrolment->updateActiveTerm();
                $promotion->update(['cancelled_at' => now(), 'cancelled_by' => Auth::id()]);
            }
        });
        session()->flash('success', count($this->selected).' promotion(s) cancelled successfully.');
        $this->clearSelection();
    }

    private function validateSelection()
    {
        $this->validate([
            'fromSectionId' => 'required|exists:sections,id',
            'fromClassId' => 'required|exists:section_classes,id',
            'toSectionId' => 'required|exists:sections,id',
            'toClassId' => 'required|exists:section_classes,id|different:fromClassId',
            'selected' => 'required|array|min:1',
        ]);
        abort_unless(SectionClass::whereKey($this->fromClassId)->where('section_id', $this->fromSectionId)->exists(), 422);
        abort_unless(SectionClass::whereKey($this->toClassId)->where('section_id', $this->toSectionId)->exists(), 422);
    }

    private function nextSession()
    {
        $current = AcademicSession::where('status', 'Active')->firstOrFail();
        $session = AcademicSession::where('id', '>', $current->id)->orderBy('id')->first();
        if (!$session) throw ValidationException::withMessages(['toClassId' => 'Configure the next academic session before promoting students.']);
        return $session->load('academicSessionTerms');
    }

    private function records()
    {
        if (!$this->fromClassId || !$this->toClassId) return collect();
        if ($this->mode === 'cancel') {
            return StudentPromotion::with('student')->whereNull('cancelled_at')
                ->whereHas('fromEnrolment', fn ($q) => $q->where('section_class_id', $this->fromClassId))
                ->whereHas('toEnrolment', fn ($q) => $q->where('section_class_id', $this->toClassId)->where('status', 'Active'))
                ->orderBy('id')->get();
        }
        return SectionClassStudent::with(['student', 'sectionClass'])->where('section_class_id', $this->fromClassId)->where('status', 'Active')
            ->whereDoesntHave('student', fn ($q) => $q->whereHas('sectionClassStudents', fn ($x) => $x->where('section_class_id', $this->toClassId)->where('status', 'Active')))
            ->orderBy('student_id')->get();
    }

    private function clearSelection() { $this->selected = []; $this->selectAll = false; $this->resetValidation(); }

    public function render()
    {
        return view('livewire.admission.promotions', [
            'sections' => Section::orderBy('name')->get(),
            'fromClasses' => SectionClass::when($this->fromSectionId, fn ($q) => $q->where('section_id', $this->fromSectionId))->orderBy('name')->get(),
            'toClasses' => SectionClass::when($this->toSectionId, fn ($q) => $q->where('section_id', $this->toSectionId))->orderBy('name')->get(),
            'records' => $this->records(),
        ]);
    }
}
