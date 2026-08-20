<?php

namespace App\Http\Livewire\Head\Teachers;

use App\Models\SectionClass;
use App\Models\SectionClassTeacher;
use App\Models\Teacher;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ClassAllocations extends Component
{
    public $allocationId, $teacherId, $classId;
    public $search = '';
    public $showForm = false;

    public function boot() { abort_unless(Auth::check() && Auth::user()->hasPermission('manage-class-teacher-allocation'), 403); }
    public function create() { $this->resetForm(); $this->showForm = true; }
    public function edit($id) { $a=SectionClassTeacher::findOrFail($id); $this->allocationId=$a->id; $this->teacherId=$a->teacher_id; $this->classId=$a->section_class_id; $this->showForm=true; }
    public function save()
    {
        $data=$this->validate(['teacherId'=>'required|exists:teachers,id','classId'=>'required|exists:section_classes,id']);
        DB::transaction(function () use ($data) {
            SectionClassTeacher::where('section_class_id',$data['classId'])->where('id','!=',$this->allocationId ?: 0)->update(['status'=>'Not Active']);
            SectionClassTeacher::updateOrCreate(['id'=>$this->allocationId],['teacher_id'=>$data['teacherId'],'section_class_id'=>$data['classId'],'status'=>'Active']);
        });
        $this->resetForm(); session()->flash('success','Class teacher allocation saved.');
    }
    public function delete($id) { SectionClassTeacher::findOrFail($id)->delete(); session()->flash('success','Class allocation removed.'); }
    public function resetForm() { $this->reset(['allocationId','teacherId','classId','showForm']); $this->resetValidation(); }
    public function render()
    {
        $allocations=SectionClassTeacher::with(['teacher.user','sectionClass.section'])->when($this->search,function($q){$s='%'.$this->search.'%';$q->whereHas('teacher.user',fn($x)=>$x->where('name','like',$s))->orWhereHas('sectionClass',fn($x)=>$x->where('name','like',$s));})->latest()->get();
        return view('livewire.head.teachers.class-allocations',['allocations'=>$allocations,'teachers'=>Teacher::with('user')->get()->sortBy('user.name'),'classes'=>SectionClass::with('section')->orderBy('name')->get()]);
    }
}
