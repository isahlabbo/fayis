<?php

namespace App\Http\Livewire\Finance\Payments;

use App\Models\AcademicSession;
use App\Models\Fee;
use App\Models\FinanceActivityLog;
use App\Models\Payment;
use App\Models\Section;
use App\Models\SectionClass;
use App\Models\SectionClassStudent;
use App\Models\SectionClassFeeItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
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
            'selectedTerms.*'=>'required|exists:terms,id', 'amount'=>'required|numeric|min:0.01',
            'mode'=>'required|in:Cash,Transfer,POS,Cheque', 'paymentDate'=>'required|date',
        ]);
        [$session, $term] = $this->selectedPeriod();
        if (!$session || !$term) { $this->addError('amount', 'Select an academic session and at least one available term.'); return; }
        $validTermIds = $session->academicSessionTerms->pluck('term_id')->map(fn($id)=>(string)$id);
        if (collect($data['selectedTerms'])->contains(fn($id)=>!$validTermIds->contains((string)$id))) {
            $this->addError('selectedTerms', 'One or more selected terms do not belong to this academic session.'); return;
        }
        $selected = SectionClassStudent::with(['student','sectionClass'])
            ->where('academic_session_id', $session->id)
            ->findOrFail($data['studentId']);
        $balance = collect($data['selectedTerms'])->sum(fn($termId)=>$this->balanceForTerm($selected,$termId,$session));
        if ($balance <= 0) { $this->addError('amount','This fee has already been paid in full.'); return; }
        if ((float)$data['amount'] > $balance) { $this->addError('amount','Payment cannot exceed the outstanding balance of '.number_format($balance,2).'.'); return; }

        $payment = DB::transaction(function () use ($data, $session) {
            $student = SectionClassStudent::where('academic_session_id', $session->id)->findOrFail($data['studentId']);
            $classFee = $student->sectionClass->sectionClassFees()->where('fee_id',$this->feeId)->firstOrFail();
            $group=(string)Str::uuid();$first=null;$total=0;
            foreach($data['selectedTerms'] as $termId){$termAmount=$this->balanceForTerm($student,$termId,$session);if($termAmount<=0)continue;$line=Payment::create(['section_class_student_id'=>$student->id,'section_class_fee_id'=>$classFee->id,'academic_session_id'=>$session->id,'term_id'=>$termId,'user_id'=>Auth::id(),'mode'=>$data['mode'],'amount'=>$termAmount,'date'=>$data['paymentDate'],'receipt_group'=>$group]);$first=$first?:$line;$total+=$termAmount;}
            abort_unless($first,422,'No outstanding amount exists for the selected terms.');
            FinanceActivityLog::record('payment',$first,'Multi-term payment recorded for '.$student->student->name,$total,['fee_id'=>$this->feeId,'terms'=>$data['selectedTerms'],'receipt_group'=>$group]);
            return $first;
        });
        $this->cancelPayment(); session()->flash('success','Payment recorded successfully.');
        return redirect()->route('finance.payments.receipt', $payment->id);
    }

    public function updatedSelectedTerms(){ $this->recalculateSelectedAmount(); }
    public function cancelRecordedPayment($paymentId)
    {
        $payment=Payment::with(['sectionClassStudent.student','sectionClassFee'])->findOrFail($paymentId);
        abort_unless((int)$payment->sectionClassFee->fee_id===(int)$this->feeId,403);
        $payments=$payment->receipt_group?Payment::where('receipt_group',$payment->receipt_group)->get():collect([$payment]);
        $total=(float)$payments->sum('amount');$studentName=optional(optional($payment->sectionClassStudent)->student)->name;
        DB::transaction(function()use($payments,$payment,$total,$studentName){FinanceActivityLog::record('payment_cancelled',$payment,'Payment cancelled for '.$studentName,-$total,['payment_ids'=>$payments->pluck('id')->all(),'receipt_group'=>$payment->receipt_group]);Payment::whereIn('id',$payments->pluck('id'))->delete();});
        session()->flash('success','Payment cancelled and the student balance restored.');
    }
    private function recalculateSelectedAmount(){if(!$this->studentId){$this->amount=0;return;}$record=SectionClassStudent::with(['student','sectionClass'])->find($this->studentId);[$session]=$this->selectedPeriod();$this->selectedBalance=$record&&$session?collect($this->selectedTerms)->sum(fn($id)=>$this->balanceForTerm($record,$id,$session)):0;$this->amount=$this->selectedBalance;}
    private function balanceForTerm($record,$termId,$session){$due=(float)SectionClassFeeItem::where('term_id',$termId)->whereHas('sectionClassFee',fn($q)=>$q->where('section_class_id',$record->section_class_id)->where('fee_id',$this->feeId))->where(fn($q)=>$q->whereNull('gender_id')->orWhere('gender_id',$record->student->gender_id))->sum('amount');$paid=(float)Payment::where('section_class_student_id',$record->id)->where('academic_session_id',$session->id)->where('term_id',$termId)->whereHas('sectionClassFee',fn($q)=>$q->where('fee_id',$this->feeId))->sum('amount');return max(0,$due-$paid);}

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

    private function dueFor($record)
    {
        [, $term] = $this->selectedPeriod(); if (!$term) return 0;
        return (float) SectionClassFeeItem::where('term_id', $term->id)
            ->whereHas('sectionClassFee', fn($query) => $query
                ->where('section_class_id', $record->section_class_id)
                ->where('fee_id', $this->feeId))
            ->where(fn($query) => $query->whereNull('gender_id')->orWhere('gender_id', $record->student->gender_id))
            ->sum('amount');
    }

    private function feeConfiguredFor($record)
    {
        [, $term] = $this->selectedPeriod(); if (!$term) return false;
        return SectionClassFeeItem::where('term_id',$term->id)
            ->whereHas('sectionClassFee',fn($query)=>$query->where('section_class_id',$record->section_class_id)->where('fee_id',$this->feeId))
            ->where(fn($query)=>$query->whereNull('gender_id')->orWhere('gender_id',$record->student->gender_id))->exists();
    }

    private function paidFor($record)
    {
        [$session,$term]=$this->selectedPeriod(); if(!$session||!$term)return 0;
        return (float) Payment::where('section_class_student_id',$record->id)->where('academic_session_id',$session->id)
            ->where('term_id',$term->id)->whereHas('sectionClassFee',fn($q)=>$q->where('fee_id',$this->feeId))->sum('amount');
    }

    public function render()
    {
        [$session,$term]=$this->selectedPeriod();
        $records=SectionClassStudent::with(['student.guardian','sectionClass.section'])
            ->when($session,fn($q)=>$q->where('academic_session_id',$session->id),fn($q)=>$q->whereRaw('1 = 0'))
            ->when($this->sectionId,fn($q)=>$q->whereHas('sectionClass',fn($x)=>$x->where('section_id',$this->sectionId)))
            ->when($this->classId,fn($q)=>$q->where('section_class_id',$this->classId))
            ->when($this->search,fn($q)=>$q->whereHas('student',fn($x)=>$x->where('name','like','%'.$this->search.'%')->orWhere('admission_no','like','%'.$this->search.'%')))->get();
        $records=$records->map(function($record)use($session,$term){$record->fee_configured=$this->feeConfiguredFor($record);$record->fee_due=$this->dueFor($record);$record->fee_paid=$this->paidFor($record);$record->fee_balance=max(0,$record->fee_due-$record->fee_paid);$record->payment_state=!$record->fee_configured?'Not configured':($record->fee_paid<=0?'Unpaid':($record->fee_balance>0?'Partial':'Paid'));$record->latest_payment_id=$session&&$term?Payment::where('section_class_student_id',$record->id)->where('academic_session_id',$session->id)->where('term_id',$term->id)->whereHas('sectionClassFee',fn($q)=>$q->where('fee_id',$this->feeId))->latest('id')->value('id'):null;return $record;})
            ->when($this->paymentStatus,fn($items)=>$items->where('payment_state',$this->paymentStatus));
        return view('livewire.finance.payments.collect',compact('records','session','term')+['sessions'=>AcademicSession::orderByDesc('id')->get(),'sessionTerms'=>$session?$session->academicSessionTerms->pluck('term')->filter():collect(),'fee'=>Fee::findOrFail($this->feeId),'sections'=>Section::orderBy('name')->get(),'classes'=>SectionClass::when($this->sectionId,fn($q)=>$q->where('section_id',$this->sectionId))->orderBy('name')->get()]);
    }
}
