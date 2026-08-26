<?php

namespace App\Http\Livewire\Finance\Payments;

use App\Models\AcademicSession;
use App\Models\AdvancePayment;
use App\Models\Fee;
use App\Models\FinanceActivityLog;
use App\Models\SectionClass;
use App\Models\SectionClassFeeItem;
use App\Models\SectionClassStudent;
use App\Models\Section;
use App\Models\Student;
use App\Models\Term;
use App\Services\Finance\ApplyAdvancePayments;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Component;

class Advance extends Component
{
    public $feeId, $studentSectionId = '', $studentClassId = '', $studentId = '', $academicSessionId = '', $classId = '';
    public $selectedTerms = [], $allocations = [], $mode = 'Cash', $paymentDate;

    public function mount($feeId)
    {
        $this->feeId = Fee::findOrFail($feeId)->id;
        $this->paymentDate = now()->toDateString();
    }

    public function boot()
    {
        abort_unless(Auth::check() && Auth::user()->hasPermission('manage-payments'), 403);
    }

    public function updatedAcademicSessionId()
    {
        $this->selectedTerms = [];
        if (!$this->academicSessionId) return;

        $session = AcademicSession::find($this->academicSessionId);
        if (!$session) return;

        foreach (Term::orderBy('id')->get() as $term) {
            $session->academicSessionTerms()->firstOrCreate(
                ['term_id'=>$term->id],
                ['status'=>'Not Active']
            );
        }
    }
    public function updatedStudentSectionId() { $this->studentClassId = ''; $this->studentId = ''; $this->allocations = []; }
    public function updatedStudentClassId() { $this->studentId = ''; $this->allocations = []; }
    public function updatedStudentId() { $this->allocations = []; }

    public function addAllocation()
    {
        $data = $this->validate([
            'studentId'=>'required|exists:students,id', 'academicSessionId'=>'required|exists:academic_sessions,id',
            'classId'=>'required|exists:section_classes,id', 'selectedTerms'=>'required|array|min:1',
            'selectedTerms.*'=>'exists:terms,id',
        ]);
        $student = Student::whereHas('sectionClassStudents', fn($query) => $query->where('section_class_id', $this->studentClassId))
            ->findOrFail($data['studentId']);
        $session = AcademicSession::with('academicSessionTerms')->findOrFail($data['academicSessionId']);
        $validTerms = $session->academicSessionTerms->pluck('term_id')->map(fn($id)=>(string)$id);
        abort_unless(collect($data['selectedTerms'])->every(fn($id)=>$validTerms->contains((string)$id)), 422);
        $class = SectionClass::findOrFail($data['classId']);

        $duplicateTermIds = AdvancePayment::where('student_id',$student->id)
            ->where('academic_session_id',$session->id)->where('section_class_id',$class->id)
            ->where('fee_id',$this->feeId)->whereIn('term_id',$data['selectedTerms'])
            ->whereIn('status',['Pending','Partially Applied','Applied'])->pluck('term_id');
        if ($duplicateTermIds->isNotEmpty()) {
            $names = $session->academicSessionTerms->whereIn('term_id',$duplicateTermIds)->pluck('term.name')->filter()->join(', ');
            $this->addError('selectedTerms','Advance payment already exists for '.($names ?: 'one or more selected terms').'.');
            return;
        }

        foreach ($data['selectedTerms'] as $termId) {
            $amount = $this->configuredAmount($class->id, $termId, $student->gender_id);
            if ($amount <= 0) continue;
            $key = implode(':', [$session->id, $class->id, $termId]);
            $this->allocations[$key] = [
                'academic_session_id'=>$session->id, 'session'=>$session->name,
                'section_class_id'=>$class->id, 'class'=>$class->name, 'term_id'=>(int)$termId,
                'term'=>optional(optional($session->academicSessionTerms->firstWhere('term_id',(int)$termId))->term)->name,
                'amount'=>$amount,
            ];
        }
        if (!$this->allocations) $this->addError('selectedTerms', 'No fee is configured for the selected class and terms.');
        $this->selectedTerms = [];
    }

    public function removeAllocation($key) { unset($this->allocations[$key]); }

    public function recordAdvancePayment()
    {
        $this->resetValidation();
        $this->allocations = [];
        $this->addAllocation();

        if (!$this->allocations || $this->getErrorBag()->isNotEmpty()) return;

        return $this->record();
    }

    public function record()
    {
        $this->validate(['studentId'=>'required|exists:students,id', 'allocations'=>'required|array|min:1',
            'mode'=>'required|in:Cash,Transfer,POS,Cheque', 'paymentDate'=>'required|date']);
        $student = Student::findOrFail($this->studentId);
        foreach ($this->allocations as $line) {
            $exists = AdvancePayment::where('student_id',$student->id)->where('fee_id',$this->feeId)
                ->where('academic_session_id',$line['academic_session_id'])->where('section_class_id',$line['section_class_id'])
                ->where('term_id',$line['term_id'])->whereIn('status',['Pending','Partially Applied','Applied'])->exists();
            if ($exists) {
                $this->addError('selectedTerms','This advance payment has already been recorded. Refresh the page to see its receipt.');
                return;
            }
        }
        $group = (string) Str::uuid();
        $first = DB::transaction(function () use ($student, $group) {
            $first = null; $total = 0;
            foreach ($this->allocations as $line) {
                $advance = AdvancePayment::create([
                    'student_id'=>$student->id, 'section_class_id'=>$line['section_class_id'], 'fee_id'=>$this->feeId,
                    'academic_session_id'=>$line['academic_session_id'], 'term_id'=>$line['term_id'], 'user_id'=>Auth::id(),
                    'receipt_group'=>$group, 'amount'=>$line['amount'], 'applied_amount'=>0,
                    'mode'=>$this->mode, 'date'=>$this->paymentDate, 'status'=>'Pending',
                ]);
                $first = $first ?: $advance; $total += (float)$line['amount'];
            }
            FinanceActivityLog::record('advance_payment', $first, 'Advance payment recorded for '.$student->name, $total, ['receipt_group'=>$group]);
            return $first;
        });
        SectionClassStudent::where('student_id', $student->id)
            ->where(function ($query) {
                foreach ($this->allocations as $line) {
                    $query->orWhere(fn($match)=>$match->where('academic_session_id',$line['academic_session_id'])->where('section_class_id',$line['section_class_id']));
                }
            })->get()->each(fn($enrolment)=>app(ApplyAdvancePayments::class)->handle($enrolment));
        return redirect()->route('finance.advance-payments.receipt', $first->id);
    }

    private function configuredAmount($classId, $termId, $genderId)
    {
        return (float) SectionClassFeeItem::where('term_id',$termId)
            ->whereHas('sectionClassFee',fn($q)=>$q->where('section_class_id',$classId)->where('fee_id',$this->feeId))
            ->where(fn($q)=>$q->whereNull('gender_id')->orWhere('gender_id',$genderId))->sum('amount');
    }

    public function getSelectedAmountProperty()
    {
        if (!$this->studentId || !$this->classId || !$this->selectedTerms) return 0;
        $student = Student::find($this->studentId);
        if (!$student) return 0;

        return collect($this->selectedTerms)->sum(
            fn($termId) => $this->configuredAmount($this->classId, $termId, $student->gender_id)
        );
    }

    public function render()
    {
        $session = $this->academicSessionId ? AcademicSession::with('academicSessionTerms.term')->find($this->academicSessionId) : null;
        return view('livewire.finance.payments.advance', [
            'fee'=>Fee::findOrFail($this->feeId),
            'sections'=>Section::orderBy('name')->get(),
            'studentClasses'=>SectionClass::when($this->studentSectionId,fn($q)=>$q->where('section_id',$this->studentSectionId))->orderBy('name')->get(),
            'students'=>$this->studentClassId ? Student::whereHas('sectionClassStudents',fn($q)=>$q->where('section_class_id',$this->studentClassId))->orderBy('name')->get() : collect(),
            'sessions'=>AcademicSession::orderByDesc('id')->get(), 'classes'=>SectionClass::orderBy('name')->get(),
            'terms'=>$session?$session->academicSessionTerms->pluck('term')->filter():collect(),
            'selectedAmount'=>$this->selectedAmount,
            'recentAdvances'=>AdvancePayment::with(['student','academicSession','sectionClass.section','term'])
                ->where('fee_id',$this->feeId)->latest()->limit(50)->get(),
        ]);
    }
}
