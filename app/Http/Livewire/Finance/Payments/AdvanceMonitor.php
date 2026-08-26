<?php

namespace App\Http\Livewire\Finance\Payments;

use App\Models\AcademicSession;
use App\Models\AdvancePayment;
use App\Models\Section;
use App\Models\SectionClass;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class AdvanceMonitor extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $search = '', $academicSessionId = '', $sectionId = '', $classId = '', $status = '';

    public function boot()
    {
        abort_unless(Auth::check() && Auth::user()->hasPermission('manage-payments'), 403);
    }

    public function updatingSearch() { $this->resetPage(); }
    public function updatedAcademicSessionId() { $this->resetPage(); }
    public function updatedSectionId() { $this->classId = ''; $this->resetPage(); }
    public function updatedClassId() { $this->resetPage(); }
    public function updatedStatus() { $this->resetPage(); }

    private function filteredQuery()
    {
        return AdvancePayment::query()
            ->when($this->search, fn($q) => $q->whereHas('student', fn($student) => $student
                ->where('name','like','%'.$this->search.'%')->orWhere('admission_no','like','%'.$this->search.'%')))
            ->when($this->academicSessionId, fn($q) => $q->where('academic_session_id',$this->academicSessionId))
            ->when($this->sectionId, fn($q) => $q->whereHas('sectionClass',fn($class)=>$class->where('section_id',$this->sectionId)))
            ->when($this->classId, fn($q) => $q->where('section_class_id',$this->classId))
            ->when($this->status, fn($q) => $q->where('status',$this->status));
    }

    public function render()
    {
        $summaryQuery = $this->filteredQuery();
        $summary = [
            'collected'=>(float)(clone $summaryQuery)->sum('amount'),
            'applied'=>(float)(clone $summaryQuery)->sum('applied_amount'),
        ];
        $summary['credit'] = max(0, $summary['collected'] - $summary['applied']);

        return view('livewire.finance.payments.advance-monitor', [
            'records'=>$this->filteredQuery()->with(['student','academicSession','sectionClass.section','fee','term'])->latest()->paginate(20),
            'summary'=>$summary, 'sessions'=>AcademicSession::orderByDesc('id')->get(),
            'sections'=>Section::orderBy('name')->get(),
            'classes'=>SectionClass::when($this->sectionId,fn($q)=>$q->where('section_id',$this->sectionId))->orderBy('name')->get(),
        ]);
    }
}
