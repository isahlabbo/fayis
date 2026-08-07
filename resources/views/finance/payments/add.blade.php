<div class="modal fade" id="addPayment" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add Payment to {{$sectionClass->name}}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="{{route('finance.payments.add',[$sectionClass->id])}}" method="post">
            @csrf
            <input type="hidden" name="type" value="{{ $feeType ?? '' }}">
            <div class="from-group mb-2">
                <label for="">Student</label>
                <select name="student" class="form-control" id="paymentStudentSelect">
                    <option value="">Select Student</option>
                    @foreach($sectionClass->sectionClassStudents->where('status', 'Active') as $classStudent)
                        <option value="{{$classStudent->id}}">{{$classStudent->student->name}}</option>
                    @endforeach
                </select>
            </div>

            <div class="from-group mb-2">
                <label for="">Description</label>
                <select name="class_fee" class="form-control" id="paymentFeeSelect">
                    <option value="">Select Fees</option>
                    @foreach($sectionClassFees as $sectionClassFee)
                        <option value="{{$sectionClassFee->id}}">{{$sectionClassFee->fee->name}}</option>
                    @endforeach
                </select>
            </div>

            <div class="from-group mb-2">
                <label for="">Term</label>
                @php $currentAcademicSessionTerm = $sectionClass->currentSessionTerm(); @endphp
                <select name="term" class="form-control" id="paymentTermSelect">
                    @if($currentAcademicSessionTerm)
                        <option value="{{$currentAcademicSessionTerm->term->id}}" selected>{{$currentAcademicSessionTerm->term->name}} (Current)</option>
                    @endif
                    @foreach(App\Models\Term::all() as $term)
                        @if(!$currentAcademicSessionTerm || $currentAcademicSessionTerm->term->id != $term->id)
                            <option value="{{$term->id}}">{{$term->name}}</option>
                        @endif
                    @endforeach
                </select>
            </div>

            <div class="from-group mb-2">
                <label for="">Amount</label>
                <input type="hidden" name="amount" id="paymentAmountInput" value="">
                <input type="text" id="paymentAmountDisplay" class="form-control" disabled value="">
            </div>

            <div class="from-group mb-2">
                <label for="">Mode of Payment</label>
                <select name="mode" class="form-control" id="">
                    <option value="">Select Mode of Payment</option>
                    <option value="Cash">Cash</option>
                    <option value="Bank Transfer">Bank Transfer</option>
                </select>
            </div>
            <div class="from-group mb-2">
                <label for="">Date</label>
                <input type="date" name="date"  value="{{ date('Y-m-d') }}" class="form-control">
            </div>
            
            <button class="btn btn-primary">Add Payment</button>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

@php
    $paymentStudents = $sectionClass->sectionClassStudents->where('status', 'Active')->map(function($classStudent) {
        return [
            'id' => $classStudent->id,
            'name' => $classStudent->student->name,
        ];
    })->values()->toArray();
    $paid = [];
    foreach ($sectionClassFees as $sectionClassFee) {
        foreach ($sectionClassFee->payments as $payment) {
            $key = $sectionClassFee->id . '_' . $payment->term_id;
            $paid[$key][] = $payment->section_class_student_id;
        }
    }
@endphp

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const feeSelect = document.getElementById('paymentFeeSelect');
        const termSelect = document.getElementById('paymentTermSelect');
        const studentSelect = document.getElementById('paymentStudentSelect');
        const amountInput = document.getElementById('paymentAmountInput');
        const amountDisplay = document.getElementById('paymentAmountDisplay');

        const students = @json($paymentStudents);
        const paidMap = @json($paid);

        const feeAmounts = {
            @foreach($sectionClassFees as $sectionClassFee)
                '{{$sectionClassFee->id}}': '{{$sectionClassFee->sectionClassFeeItems->sum('amount')}}',
            @endforeach
        };

        const updateAmount = () => {
            const selected = feeSelect.value;
            const amount = feeAmounts[selected] || '';
            amountInput.value = amount;
            amountDisplay.value = amount ? parseFloat(amount).toFixed(2) : '';
        };

        const populateStudentOptions = () => {
            const selectedFee = feeSelect.value;
            const selectedTerm = termSelect.value;
            const key = selectedFee && selectedTerm ? `${selectedFee}_${selectedTerm}` : null;
            const paid = key && paidMap[key] ? paidMap[key] : [];

            studentSelect.innerHTML = '';

            const defaultOption = document.createElement('option');
            defaultOption.value = '';
            defaultOption.text = 'Select Student';
            studentSelect.appendChild(defaultOption);

            const eligibleStudents = students.filter(student => !paid.includes(student.id));

            if (selectedFee && selectedTerm && eligibleStudents.length === 0) {
                const option = document.createElement('option');
                option.value = '';
                option.disabled = true;
                option.text = 'All active students already have payments for this fee and term';
                studentSelect.appendChild(option);
                return;
            }

            eligibleStudents.forEach(student => {
                const option = document.createElement('option');
                option.value = student.id;
                option.text = student.name;
                studentSelect.appendChild(option);
            });
        };

        feeSelect.addEventListener('change', () => {
            updateAmount();
            populateStudentOptions();
        });

        termSelect.addEventListener('change', populateStudentOptions);

        updateAmount();
        populateStudentOptions();
    });
</script>