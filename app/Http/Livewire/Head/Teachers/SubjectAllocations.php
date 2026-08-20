<?php

namespace App\Http\Livewire\Head\Teachers;

use App\Models\SectionClassSubject;
use App\Models\SectionClassSubjectTeacher;
use App\Models\Teacher;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class SubjectAllocations extends Component
{
    public $allocationId, $teacherId, $classSubjectId;
    public $search = '';
    public $showForm = false;

    public function boot() { abort_unless(Auth::check() && Auth::user()->hasPermission('manage-class-subjects'), 403); }
    public function create() { $this->resetForm(); $this->showForm=true; }
    public function edit($id) { $a=SectionClassSubjectTeacher::findOrFail($id);$this->allocationId=$a->id;$this->teacherId=$a->teacher_id;$this->classSubjectId=$a->section_class_subject_id;$this->showForm=true; }
    public function save()
    {
        $data=$this->validate(['teacherId'=>'required|exists:teachers,id','classSubjectId'=>'required|exists:section_class_subjects,id']);
        DB::transaction(function () use($data){SectionClassSubjectTeacher::where('section_class_subject_id',$data['classSubjectId'])->where('id','!=',$this->allocationId ?: 0)->update(['status'=>'Not Active']);SectionClassSubjectTeacher::updateOrCreate(['id'=>$this->allocationId],['teacher_id'=>$data['teacherId'],'section_class_subject_id'=>$data['classSubjectId'],'status'=>'Active']);});
        $this->resetForm();session()->flash('success','Subject allocation saved.');
    }
    public function toggle($id)
    {
        $allocation = SectionClassSubjectTeacher::findOrFail($id);

        DB::transaction(function () use ($allocation) {
            if ($allocation->status !== 'Active') {
                SectionClassSubjectTeacher::where('section_class_subject_id', $allocation->section_class_subject_id)
                    ->whereKeyNot($allocation->id)
                    ->update(['status' => 'Not Active']);
            }

            $allocation->update(['status' => $allocation->status === 'Active' ? 'Not Active' : 'Active']);
        });
    }
    public function delete($id) { $a=SectionClassSubjectTeacher::findOrFail($id);if($a->subjectTeacherTermlyUploads()->exists()){session()->flash('error','This allocation has result uploads and cannot be deleted; deactivate it instead.');return;}$a->delete();session()->flash('success','Subject allocation removed.'); }
    public function resetForm() { $this->reset(['allocationId','teacherId','classSubjectId','showForm']);$this->resetValidation(); }
    public function render()
    {
        $allocations=SectionClassSubjectTeacher::with(['teacher.user','sectionClassSubject.subject','sectionClassSubject.sectionClass'])->when($this->search,function($q){$s='%'.$this->search.'%';$q->whereHas('teacher.user',fn($x)=>$x->where('name','like',$s))->orWhereHas('sectionClassSubject.subject',fn($x)=>$x->where('name','like',$s))->orWhereHas('sectionClassSubject.sectionClass',fn($x)=>$x->where('name','like',$s));})->latest()->get();
        return view('livewire.head.teachers.subject-allocations',['allocations'=>$allocations,'teachers'=>Teacher::with('user')->get()->sortBy('user.name'),'classSubjects'=>SectionClassSubject::with(['subject','sectionClass'])->get()]);
    }
}
