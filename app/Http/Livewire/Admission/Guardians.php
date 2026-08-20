<?php

namespace App\Http\Livewire\Admission;

use App\Models\Guardian;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Guardians extends Component
{
    public $search='';
    public function boot() { abort_unless(Auth::check() && Auth::user()->hasPermission('manage-admissions'), 403); }
    public function render()
    {
        $guardians=collect();
        if(strlen(trim($this->search))>=2) $guardians=Guardian::with(['students.sectionClassStudents.sectionClass.section'])->where(fn($q)=>$q->where('name','like','%'.$this->search.'%')->orWhere('phone','like','%'.$this->search.'%'))->orderBy('name')->get();
        return view('livewire.admission.guardians',compact('guardians'));
    }
}
