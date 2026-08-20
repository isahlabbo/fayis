<?php

namespace App\Http\Livewire\Admission;

use App\Models\AcademicSession;
use App\Models\Guardian;
use App\Models\Section;
use App\Models\SectionClass;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Applications extends Component
{
    public $studentId, $name, $dateOfBirth, $genderId, $classId;
    public $guardianName, $guardianPhone, $guardianEmail, $guardianAddress;
    public $search = '', $showForm = false;

    public function boot() { abort_unless(Auth::check() && Auth::user()->hasPermission('manage-admissions'), 403); }
    public function create() { $this->resetForm(); $this->showForm = true; }
    public function edit($id)
    {
        $student = Student::with('guardian')->where('admission_status', 'Pending')->findOrFail($id);
        $this->studentId = $student->id; $this->name = $student->name; $this->dateOfBirth = $student->date_of_birth;
        $this->genderId = $student->gender_id; $this->classId = $student->desired_section_class_id;
        $this->guardianName = optional($student->guardian)->name; $this->guardianPhone = optional($student->guardian)->phone;
        $this->guardianEmail = optional($student->guardian)->email; $this->guardianAddress = optional($student->guardian)->address;
        $this->showForm = true;
    }
    public function save()
    {
        $data = $this->validate([
            'name'=>'required|string|max:255', 'dateOfBirth'=>'required|date', 'genderId'=>'nullable|exists:genders,id',
            'classId'=>'required|exists:section_classes,id', 'guardianName'=>'required|string|max:255',
            'guardianPhone'=>'required|string|max:50', 'guardianEmail'=>'nullable|email|max:255', 'guardianAddress'=>'nullable|string|max:500',
        ]);
        $session = AcademicSession::where('status', 'Active')->first();
        if (!$session) { $this->addError('classId', 'Configure an active academic session before registering applications.'); return; }
        DB::transaction(function () use ($data, $session) {
            $guardian = Guardian::firstOrCreate(['phone'=>$data['guardianPhone']], ['name'=>$data['guardianName']]);
            $guardian->update(['name'=>$data['guardianName'], 'email'=>$data['guardianEmail'], 'address'=>$data['guardianAddress']]);
            Student::updateOrCreate(['id'=>$this->studentId], [
                'guardian_id'=>$guardian->id, 'academic_session_id'=>$session->id, 'name'=>strtoupper($data['name']),
                'date_of_birth'=>$data['dateOfBirth'], 'gender_id'=>$data['genderId'],
                'desired_section_class_id'=>$data['classId'], 'admission_status'=>'Pending', 'admission_no'=>null,
            ]);
        });
        $this->resetForm(); session()->flash('success', 'Application saved. The student has not been assigned to a class.');
    }
    public function delete($id) { Student::where('admission_status','Pending')->whereDoesntHave('sectionClassStudents')->findOrFail($id)->delete(); session()->flash('success','Application removed.'); }
    public function resetForm() { $this->reset(['studentId','name','dateOfBirth','genderId','classId','guardianName','guardianPhone','guardianEmail','guardianAddress','showForm']); $this->resetValidation(); }
    public function render()
    {
        $applications = Student::with(['guardian','desiredSectionClass.section'])->where('admission_status','Pending')
            ->when($this->search, fn($q) => $q->where(fn($x) => $x->where('name','like','%'.$this->search.'%')->orWhereHas('guardian',fn($g)=>$g->where('phone','like','%'.$this->search.'%'))))
            ->latest()->get();
        $stats = Section::withCount(['sectionClasses as application_count'=>fn($q)=>$q->join('students','students.desired_section_class_id','=','section_classes.id')->where('students.admission_status','Pending')])->get();
        return view('livewire.admission.applications', compact('applications','stats') + ['classes'=>SectionClass::with('section')->orderBy('name')->get()]);
    }
}
