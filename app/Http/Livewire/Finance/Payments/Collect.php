<?php

namespace App\Http\Livewire\Finance\Payments;

use App\Models\AcademicSession;
use App\Models\Fee;
use App\Models\FinanceActivityLog;
use App\Models\Payment;
use App\Models\Section;
use App\Models\SectionClass;
use App\Models\SectionClassStudent;
use App\Models\AdvancePayment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Services\Finance\FeeBalances;
use App\Services\Finance\RecordFeePayment;
use Livewire\Component;

class Collect extends Component
{
    public $feeId, $sectionId = '', $classId = '', $paymentStatus = '', $search = '';
    public $academicSessionId = '', $termId = '';
    public $studentId, $selectedStudentName, $selectedBalance = 0, $selectedTerms = [], $amount, $mode = 'Cash', $paymentDate;

    public function mount($feeId)
    {
        $this->feeId = Fee::findOrFail($feeId)->id;
        $this->paymentDate = now()->toDateString();
        $session = AcademicSession::with('academicSessionTerms')->where('status', 'Active')->first();
        $this->academicSessionId = $session ? (string) $session->id : '';
        $activeTerm = $session ? $session->academicSessionTerms->firstWhere('status', 'Active') : null;
        $this->termId = $activeTerm ? (string) $activeTerm->term_id : '';
    }

    public function boot()
    {
        abort_unless(Auth::check() && Auth::user()->hasPermission('manage-payments'), 403);
    }

    public function updatedSectionId() { $this->classId = ''; }

    public function updatedAcademicSessionId()
    {
        $session = $this->selectedSession();
        $activeTerm = $session ? $session->academicSessionTerms->firstWhere('status', 'Active') : null;
        $firstTerm = $session ? $session->academicSessionTerms->first() : null;
        $this->termId = (string) optional($activeTerm ?: $firstTerm)->term_id;
        $this->cancelPayment();
    }

    public function updatedTermId() { $this->cancelPayment(); }

    public function selectStudent($id)
    {
        $record = SectionClassStudent::with('student')
            ->where('academic_session_id', $this->academicSessionId)
            ->findOrFail($id);
        $this->studentId = $record->id;
        $this->selectedStudentName = $record->student->name;
        [, $term] = $this->selectedPeriod();
        $this->selectedTerms = $term ? [(string)$term->id] : [];
        $this->recalculateSelectedAmount();
        $this->dispatchBrowserEvent('payment-form-opened');
    }

    public function cancelPayment()
    {
        $this->reset(['studentId', 'selectedStudentName', 'selectedBalance', 'selectedTerms', 'amount']);
        $this->mode = 'Cash'; $this->paymentDate = now()->toDateString(); $this->resetValidation();
    }

    public function recordPayment()
    {
        $data = $this->validate([
            'studentId'=>'required|exists:section_class_students,id', 'selectedTerms'=>'required|array|min:1',
            'selectedTerms.*'=>'required|integer|distinct|exists:terms,id', 'amount'=>'required|numeric|min:0.01|regex:/^\d+(\.\d{1,2})?$/',
            'mode'=>'required|in:Cash,Transfer,POS,Cheque', 'paymentDate'=>'required|date',
        ]);
        [$session, $term] = $this->selectedPeriod();
        if (!$session || !$term) { $this->addError('amount', 'Select an academic session and at least one available term.'); return; }
        $validTermIds = $session->academicSessionTerms->pluck('term_id')->map(fn($id)=>(string)$id);
        if (collect($data['selectedTerms'])->contains(fn($id)=>!$validTermIds->contains((string)$id))) {
            $this->addError('selectedTerms', 'One or more selected terms do not belong to this academic session.'); return;
        }
        $payment = app(RecordFeePayment::class)->handle(
            $data['studentId'], $session->id, $this->feeId, $data['selectedTerms'],
            $data['amount'], $data['mode'], $data['paymentDate']
        );
        $this->cancelPayment(); session()->flash('success','Payment recorded successfully.');
        return redirect()->route('finance.payments.receipt', $payment->id);
    }

    public function updatedSelectedTerms(){ $this->recalculateSelectedAmount(); }
    public function cancelRecordedPayment($paymentId)
    {
        $cancelled = DB::transaction(function () use ($paymentId) {
            $payment = Payment::findOrFail($paymentId);
            SectionClassStudent::whereKey($payment->section_class_student_id)->lockForUpdate()->firstOrFail();
            $payment = Payment::with(['sectionClassStudent.student', 'sectionClassFee'])->lockForUpdate()->findOrFail($paymentId);
            abort_unless((int) $payment->sectionClassFee->fee_id === (int) $this->feeId, 403);
            $payments = $payment->receipt_group
                ? Payment::where('receipt_group', $payment->receipt_group)->lockForUpdate()->get()
                : collect([$payment]);
            $paymentIds = $payments->pluck('id');
            $appliedAdvance = AdvancePayment::whereIn('applied_payment_id', $paymentIds)->exists()
                || FinanceActivityLog::where('activity_type', 'advance_payment_applied')
                    ->whereIn('metadata->payment_id', $paymentIds)->exists();
            if ($appliedAdvance) {
                $this->addError('payment', 'This receipt includes an applied advance. Correct it from Advance Payments so the advance balance stays consistent.');
                return false;
            }
            FinanceActivityLog::record('payment_cancelled', $payment,
                'Payment cancelled for '.$payment->sectionClassStudent->student->name,
                -(float) $payments->sum('amount'), [
                    'payment_ids' => $paymentIds->all(), 'receipt_group' => $payment->receipt_group,
                ]);
            Payment::whereIn('id', $paymentIds)->delete();
            return true;
        });
        if ($cancelled) session()->flash('success', 'Payment cancelled and the student balance restored.');
    }
    private function recalculateSelectedAmount(){if(!$this->studentId){$this->amount=0;return;}$record=SectionClassStudent::with(['student','sectionClass'])->find($this->studentId);[$session]=$this->selectedPeriod();$this->selectedBalance=$record&&$session?collect($this->selectedTerms)->sum(fn($id)=>$this->balanceForTerm($record,$id,$session)):0;$this->amount=$this->selectedBalance;}
    private function balanceForTerm($record, $termId, $session)
    {
        $balance = app(FeeBalances::class)->forEnrolment($record)
            ->where('fee_id', $this->feeId)->firstWhere('term_id', $termId);
        return $balance->balance ?? 0;
    }
    private function selectedSession()
    {
        return $this->academicSessionId
            ? AcademicSession::with(['academicSessionTerms' => fn($query) => $query->orderBy('term_id'), 'academicSessionTerms.term'])->find($this->academicSessionId)
            : null;
    }

    private function selectedPeriod()
    {
        $session = $this->selectedSession();
        $sessionTerm = $session ? $session->academicSessionTerms->firstWhere('term_id', (int) $this->termId) : null;
        return [$session, optional($sessionTerm)->term];
    }

    public function render()
    {
        [$session,$term]=$this->selectedPeriod();
        $records=SectionClassStudent::with(array_merge(FeeBalances::RELATIONS, ['sectionClass.section']))
            ->when($session,fn($q)=>$q->where('academic_session_id',$session->id),fn($q)=>$q->whereRaw('1 = 0'))
            ->when($this->sectionId,fn($q)=>$q->whereHas('sectionClass',fn($x)=>$x->where('section_id',$this->sectionId)))
            ->when($this->classId,fn($q)=>$q->where('section_class_id',$this->classId))
            ->when($this->search,fn($q)=>$q->whereHas('student',fn($x)=>$x->where('name','like','%'.$this->search.'%')->orWhere('admission_no','like','%'.$this->search.'%')))->get();
        $records=$records->map(function($record)use($session,$term){$balance=$term?app(FeeBalances::class)->forEnrolment($record)->where('fee_id',$this->feeId)->firstWhere('term_id',$term->id):null;$record->fee_configured=(bool)$balance;$record->fee_due=$balance->due??0;$record->fee_paid=$balance->paid??0;$record->fee_balance=max(0,$record->fee_due-$record->fee_paid);$record->payment_state=!$record->fee_configured?'Not configured':($record->fee_paid<=0?'Unpaid':($record->fee_balance>0?'Partial':'Paid'));$record->latest_payment_id=$session&&$term?Payment::where('section_class_student_id',$record->id)->where('academic_session_id',$session->id)->where('term_id',$term->id)->whereHas('sectionClassFee',fn($q)=>$q->where('fee_id',$this->feeId))->latest('id')->value('id'):null;return $record;})
            ->when($this->paymentStatus,fn($items)=>$items->where('payment_state',$this->paymentStatus));
        return view('livewire.finance.payments.collect',compact('records','session','term')+['sessions'=>AcademicSession::orderByDesc('id')->get(),'sessionTerms'=>$session?$session->academicSessionTerms->pluck('term')->filter():collect(),'fee'=>Fee::findOrFail($this->feeId),'sections'=>Section::orderBy('name')->get(),'classes'=>SectionClass::when($this->sectionId,fn($q)=>$q->where('section_id',$this->sectionId))->orderBy('name')->get()]);
    }
}
