<?php

namespace App\Http\Livewire\Patron\Statistics;

use App\Models\Section;
use App\Models\SectionClass;
use App\Models\SectionClassStudent;
use Livewire\Component;

class Students extends Component
{
    public $search = '';
    public $selectedSection = '';
    public $selectedClass = '';
    public $selectedGender = '';
    public $sections;
    public $classes;

    public function mount()
    {
        $this->sections = Section::orderBy('name')->get();
        $this->classes = collect();

        if ($this->selectedSection) {
            $this->classes = SectionClass::where('section_id', $this->selectedSection)
                ->orderBy('name')
                ->get();
        }
    }

    public function updatedSelectedSection($sectionId)
    {
        $this->selectedClass = '';
        $this->classes = $sectionId
            ? SectionClass::where('section_id', $sectionId)->orderBy('name')->get()
            : collect();
    }

    public function render()
    {
        $students = SectionClassStudent::query()
            ->where('status', 'Active')
            ->with(['student.guardian', 'sectionClass.section'])
            ->when($this->selectedSection, function ($query) {
                $query->whereHas('sectionClass.section', function ($sectionQuery) {
                    $sectionQuery->where('id', $this->selectedSection);
                });
            })
            ->when($this->selectedClass, function ($query) {
                $query->where('section_class_id', $this->selectedClass);
            })
            ->when($this->selectedGender, function ($query) {
                $query->whereHas('student', function ($studentQuery) {
                    $studentQuery->where('gender_id', $this->selectedGender);
                });
            })
            ->when($this->search, function ($query) {
                $search = trim($this->search);
                $query->whereHas('student', function ($studentQuery) use ($search) {
                    $studentQuery->where('name', 'like', "%{$search}%")
                        ->orWhere('admission_no', 'like', "%{$search}%")
                        ->orWhereHas('guardian', function ($guardianQuery) use ($search) {
                            $guardianQuery->where('name', 'like', "%{$search}%")
                                ->orWhere('phone', 'like', "%{$search}%");
                        });
                });
            })
            ->orderBy('id', 'desc')
            ->get();

        $summary = [
            'total' => $students->count(),
            'male' => $students->filter(fn ($student) => data_get($student, 'student.gender_id') == 1)->count(),
            'female' => $students->filter(fn ($student) => data_get($student, 'student.gender_id') == 2)->count(),
        ];

        return view('livewire.patron.statistics.students', compact('students', 'summary'));
    }
}
