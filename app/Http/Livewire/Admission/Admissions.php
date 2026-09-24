<?php

namespace App\Http\Livewire\Admission;

use App\Models\SectionClass;
use App\Models\AcademicSession;
use App\Models\Section;
use App\Models\SectionClassStudent;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Admissions extends Component
{
    public $search = '', $studentId, $classId;
    public $filterSessionId = '', $filterSectionId = '', $filterClassId = '';

    public function updatedFilterSectionId() { $this->filterClassId = ''; }
    public function updated($property)
    {
        if (in_array($property, ['search', 'filterSessionId', 'filterSectionId', 'filterClassId'], true)) $this->cancel();
    }
    public function resetFilters()
    {
        $this->reset(['search', 'filterSessionId', 'filterSectionId', 'filterClassId']);
        $this->cancel();
    }
    public function boot() { abort_unless(Auth::check() && Auth::user()->hasPermission('manage-admissions'), 403); }
    public function select($id) { $student=Student::where('admission_status','Pending')->findOrFail($id); $this->studentId=$id; $this->classId=$student->desired_section_class_id; }
    public function cancel() { $this->reset(['studentId','classId']); $this->resetValidation(); }
    public function approve()
    {
        $data=$this->validate(['studentId'=>'required|exists:students,id','classId'=>'required|exists:section_classes,id']);
        DB::transaction(function () use ($data) {
            $student=Student::where('admission_status','Pending')->lockForUpdate()->findOrFail($data['studentId']);
            $class=SectionClass::findOrFail($data['classId']); $session=$student->currentSession();
            abort_unless($session, 422, 'No active academic session is configured.');
            $student->sectionClassStudents()->where('status','Active')->update(['status'=>'Not Active']);
            $enrolment=SectionClassStudent::firstOrCreate(['student_id'=>$student->id,'section_class_id'=>$class->id,'academic_session_id'=>$session->id],['status'=>'Active']);
            $enrolment->update(['status'=>'Active']);
            foreach($session->academicSessionTerms as $term) $enrolment->sectionClassStudentTerms()->firstOrCreate(['academic_session_term_id'=>$term->id],['status'=>$term->status==='Active'?'Active':'Not Active']);
            $student->update(['desired_section_class_id'=>$class->id,'admission_status'=>'Admitted','admission_no'=>$student->admission_no ?: $class->generateAdmissionNo()]);
        });
        $this->cancel(); session()->flash('success','Application approved and student assigned to class.');
    }
    public function render()
    {
        $applications = Student::with(['guardian','gender','desiredSectionClass.section'])->where('admission_status','Pending')
            ->whereHas('desiredSectionClass')
            ->when($this->filterSessionId, fn($q) => $q->where('academic_session_id', $this->filterSessionId))
            ->when($this->filterSectionId, fn($q) => $q->whereHas('desiredSectionClass', fn($class) => $class->where('section_id', $this->filterSectionId)))
            ->when($this->filterClassId, fn($q) => $q->where('desired_section_class_id', $this->filterClassId))
            ->when($this->search, fn($q) => $q->where(fn($x) => $x->where('name','like','%'.$this->search.'%')->orWhereHas('guardian',fn($g)=>$g->where('phone','like','%'.$this->search.'%'))))
            ->latest()->get();
        $statistics = [
            'total' => $applications->count(),
            'male' => $applications->filter(fn($student) => strtolower(trim(optional($student->gender)->name ?? '')) === 'male')->count(),
            'female' => $applications->filter(fn($student) => strtolower(trim(optional($student->gender)->name ?? '')) === 'female')->count(),
        ];
        $statistics['unspecified'] = $statistics['total'] - $statistics['male'] - $statistics['female'];
        $sections = Section::orderBy('name')->get();
        return view('livewire.admission.admissions', compact('applications', 'statistics', 'sections') + [
            'classes' => SectionClass::with('section')->orderBy('name')->get(),
            'filterClasses' => SectionClass::when($this->filterSectionId, fn($q) => $q->where('section_id', $this->filterSectionId))->orderBy('name')->get(),
            'sessions' => AcademicSession::orderByDesc('id')->get(),
        ]);
    }
}
