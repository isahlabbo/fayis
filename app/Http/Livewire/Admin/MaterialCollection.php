<?php

namespace App\Http\Livewire\Admin;

use App\Models\AcademicSession;
use App\Models\Section;
use App\Models\SectionClass;
use App\Models\SectionClassStudent;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class MaterialCollection extends Component
{
    public $academicSessionId = '';
    public $sectionId = '';
    public $classId = '';
    public $search = '';
    public $selected = [];

    // Session/class the collected material is being prepared for (e.g. the next session's class before promotion happens).
    public $targetSessionId = '';
    public $targetSectionId = '';
    public $targetClassId = '';

    public function boot()
    {
        abort_unless(Auth::check() && Auth::user()->hasPermission('manage-material-collection'), 403);
    }

    public function mount()
    {
        $current = AcademicSession::where('status', 'Active')->first();
        $this->academicSessionId = $current->id ?? optional(AcademicSession::orderByDesc('id')->first())->id;
    }

    public function updatedSectionId()
    {
        $this->classId = '';
    }

    public function updatedTargetSectionId()
    {
        $this->targetClassId = '';
    }

    public function toggleStudent($sectionClassStudentId)
    {
        if (in_array($sectionClassStudentId, $this->selected)) {
            $this->selected = array_values(array_diff($this->selected, [$sectionClassStudentId]));
        } else {
            $this->selected[] = $sectionClassStudentId;
        }
    }

    public function removeStudent($sectionClassStudentId)
    {
        $this->selected = array_values(array_diff($this->selected, [$sectionClassStudentId]));
    }

    public function clearSelection()
    {
        $this->selected = [];
    }

    public function toggleSelectAll()
    {
        $visibleIds = $this->filteredStudentsQuery()->pluck('id')->all();

        if (empty(array_diff($visibleIds, $this->selected))) {
            $this->selected = array_values(array_diff($this->selected, $visibleIds));
        } else {
            $this->selected = array_values(array_unique(array_merge($this->selected, $visibleIds)));
        }
    }

    private function filteredStudentsQuery()
    {
        return SectionClassStudent::with(['student.guardian', 'sectionClass.section'])
            ->whereHas('student')
            ->where('status', 'Active')
            ->when($this->academicSessionId, fn ($q) => $q->where('academic_session_id', $this->academicSessionId))
            ->when($this->sectionId, fn ($q) => $q->whereHas('sectionClass', fn ($x) => $x->where('section_id', $this->sectionId)))
            ->when($this->classId, fn ($q) => $q->where('section_class_id', $this->classId))
            ->when($this->search, fn ($q) => $q->whereHas('student', fn ($x) => $x->where('name', 'like', '%'.$this->search.'%')->orWhere('admission_no', 'like', '%'.$this->search.'%')));
    }

    public function render()
    {
        $students = $this->filteredStudentsQuery()->get()->sortBy('student.name');

        $selectedRecords = SectionClassStudent::with(['student.guardian', 'sectionClass.section'])
            ->whereIn('id', $this->selected)
            ->get()
            ->sortBy('student.name');

        return view('livewire.admin.material-collection', [
            'sessions' => AcademicSession::orderByDesc('id')->get(),
            'sections' => Section::orderBy('name')->get(),
            'classes' => SectionClass::when($this->sectionId, fn ($q) => $q->where('section_id', $this->sectionId))->orderBy('name')->get(),
            'targetSections' => Section::orderBy('name')->get(),
            'targetClasses' => SectionClass::when($this->targetSectionId, fn ($q) => $q->where('section_id', $this->targetSectionId))->orderBy('name')->get(),
            'students' => $students,
            'selectedRecords' => $selectedRecords,
        ]);
    }
}
