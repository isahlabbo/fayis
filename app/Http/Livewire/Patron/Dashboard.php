<?php

namespace App\Http\Livewire\Patron;

use App\Models\Section;
use Livewire\Component;

class Dashboard extends Component
{
    public $sections;

    public function mount()
    {
        $this->sections = Section::with(['sectionClasses.sectionClassStudents.student', 'sectionClasses.sectionClassSubjects', 'sectionClasses.sectionClassTeachers'])
            ->orderBy('name')
            ->get();
    }

    public function render()
    {
        return view('livewire.patron.dashboard', ['sections' => $this->sections]);
    }
}
