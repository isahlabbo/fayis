<?php

namespace App\Http\Livewire\Patron\Statistics;

use App\Models\Section;
use App\Models\Teacher;
use Livewire\Component;

class Teachers extends Component
{
    public $search = '';
    public $selectedSection = '';
    public $sections;

    public function mount()
    {
        $this->sections = Section::orderBy('name')->get();
    }

    public function render()
    {
        $teachers = Teacher::query()
            ->with(['user', 'sectionClassTeachers.sectionClass.section', 'sectionClassSubjectTeachers.sectionClassSubject.subject'])
            ->when($this->selectedSection, function ($query) {
                $query->whereHas('sectionClassTeachers.sectionClass.section', function ($sectionQuery) {
                    $sectionQuery->where('id', $this->selectedSection);
                });
            })
            ->when($this->search, function ($query) {
                $search = trim($this->search);
                $query->whereHas('user', function ($userQuery) use ($search) {
                    $userQuery->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->orWhere('id', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->join('users', 'teachers.user_id', '=', 'users.id')
            ->orderBy('users.name')
            ->select('teachers.*')
            ->get();

        return view('livewire.patron.statistics.teachers', compact('teachers'));
    }
}
