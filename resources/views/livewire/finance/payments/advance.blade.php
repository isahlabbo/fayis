<div class="container-fluid py-3">
    <h3>{{ $fee->name }} Advance Payment</h3>
    <p class="text-muted">Allocate payment to future sessions, classes and terms. It will activate when the student enters the matching class.</p>
    <div class="card mb-3"><div class="card-body">
        <h5 class="mb-3"><i class="fas fa-user-graduate text-primary mr-2"></i>Who are you paying for?</h5>
        <div class="form-row">
            <div class="form-group col-md-4"><label>Current section</label><select wire:model="studentSectionId" class="form-control"><option value="">Select section</option>@foreach($sections as $section)<option value="{{$section->id}}">{{$section->name}}</option>@endforeach</select></div>
            <div class="form-group col-md-4"><label>Current class</label><select wire:model="studentClassId" class="form-control" @if(!$studentSectionId) disabled @endif><option value="">Select class</option>@foreach($studentClasses as $class)<option value="{{$class->id}}">{{$class->name}}</option>@endforeach</select></div>
            <div class="form-group col-md-4"><label>Student</label><select wire:model="studentId" class="form-control" @if(!$studentClassId) disabled @endif><option value="">Select student</option>@foreach($students as $student)<option value="{{$student->id}}">{{$student->name}} ({{$student->admission_no}})</option>@endforeach</select>@error('studentId')<small class="text-danger">{{$message}}</small>@enderror</div>
        </div>
        <hr>
        <h5 class="mb-3"><i class="fas fa-forward text-success mr-2"></i>What are you paying to?</h5>
        <div class="form-row">
            <div class="form-group col-md-3"><label>Future session</label><select wire:model="academicSessionId" class="form-control"><option value="">Select session</option>@foreach($sessions as $session)<option value="{{$session->id}}">{{$session->name}}</option>@endforeach</select>@error('academicSessionId')<small class="text-danger">{{$message}}</small>@enderror</div>
            <div class="form-group col-md-3"><label>Intended class</label><select wire:model="classId" class="form-control"><option value="">Select class</option>@foreach($classes as $class)<option value="{{$class->id}}">{{$class->name}}</option>@endforeach</select>@error('classId')<small class="text-danger">{{$message}}</small>@enderror</div>
            <div class="form-group col-md-3"><label>Terms</label><div class="d-flex flex-wrap pt-2">@foreach($terms as $term)<label class="mr-3"><input type="checkbox" wire:model="selectedTerms" value="{{$term->id}}"> {{$term->name}}</label>@endforeach</div>@error('selectedTerms')<small class="text-danger d-block">{{$message}}</small>@enderror</div>
            <div class="form-group col-md-3"><label>Amount</label><div class="form-control font-weight-bold bg-light">{{number_format($selectedAmount,2)}}</div><small class="text-muted">Total for selected terms</small></div>
        </div>
        <div class="form-row mt-2"><div class="form-group col-md-4"><label>Payment method</label><select wire:model="mode" class="form-control"><option>Cash</option><option>Transfer</option><option>POS</option><option>Cheque</option></select></div><div class="form-group col-md-4"><label>Payment date</label><input type="date" wire:model="paymentDate" class="form-control"></div></div>
        <button wire:click="recordAdvancePayment" wire:loading.attr="disabled" class="btn btn-success"><i class="fas fa-receipt mr-1"></i>Record Advance Payment</button>
        <span wire:loading wire:target="recordAdvancePayment" class="ml-2 text-muted">Recording payment...</span>
    </div></div>
    <div class="card shadow-sm"><div class="card-header bg-white"><strong>Recorded Advance Payments</strong><small class="text-muted d-block">Latest 50 {{strtolower($fee->name)}} advance-payment entries</small></div><div class="table-responsive"><table class="table table-hover mb-0"><thead class="thead-light"><tr><th>Student</th><th>Destination</th><th>Term</th><th>Amount</th><th>Applied</th><th>Credit</th><th>Status</th><th></th></tr></thead><tbody>
        @forelse($recentAdvances as $advance)<tr><td>{{$advance->student->name}}<br><small class="text-muted">{{$advance->student->admission_no}}</small></td><td>{{$advance->academicSession->name}}<br><small>{{optional($advance->sectionClass->section)->name}} / {{$advance->sectionClass->name}}</small></td><td>{{$advance->term->name}}</td><td>{{number_format($advance->amount,2)}}</td><td>{{number_format($advance->applied_amount,2)}}</td><td>{{number_format($advance->remaining_amount,2)}}</td><td><span class="badge badge-{{$advance->status==='Applied'?'success':($advance->status==='Pending'?'warning':'info')}}">{{$advance->status}}</span></td><td><a target="_blank" href="{{route('finance.advance-payments.receipt',$advance->id)}}" class="btn btn-sm btn-outline-primary"><i class="fas fa-print mr-1"></i>Receipt</a></td></tr>
        @empty<tr><td colspan="8" class="text-center text-muted py-5">No advance payments have been recorded for this fee category.</td></tr>@endforelse
    </tbody></table></div></div>
</div>
