<?php

namespace App\Http\Livewire\Admission;

use App\Models\Section;
use App\Models\SectionClass;
use App\Models\SectionClassStudent;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Students extends Component
{
    public $search='', $sectionId='', $classId='';
    public function boot() { abort_unless(Auth::check() && Auth::user()->hasPermission('manage-admissions'), 403); }
    public function updatedSectionId() { $this->classId=''; }
    public function render()
    {
        $records=SectionClassStudent::with(['student.guardian','sectionClass.section'])->where('status','Active')
            ->when($this->sectionId,fn($q)=>$q->whereHas('sectionClass',fn($x)=>$x->where('section_id',$this->sectionId)))
            ->when($this->classId,fn($q)=>$q->where('section_class_id',$this->classId))
            ->when($this->search,fn($q)=>$q->whereHas('student',fn($x)=>$x->where('name','like','%'.$this->search.'%')->orWhere('admission_no','like','%'.$this->search.'%')))->latest()->get();
        return view('livewire.admission.students',compact('records')+['sections'=>Section::orderBy('name')->get(),'classes'=>SectionClass::when($this->sectionId,fn($q)=>$q->where('section_id',$this->sectionId))->orderBy('name')->get()]);
    }
}
