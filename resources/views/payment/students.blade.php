<x-guest-layout>
    @section('title')
        {{$class->name}} student e-payment
    @endsection
    
    
    @section('content')
        <h5 class="text text-primary m-4">{{$class->name}} Students</h5>
        <table class="table table-striped table-sm">
            <thead>
                <tr>
                    <th>SN</th>
                    <th>ADMISSION NO</th>
                    <th>PROFILE CODE</th>
                    <th>NAME</th>
                    <th>LGA</th>
                    <th>STATE</th>
                    <th></th>
                </tr>
            </thead>
           
            <tbody>
            @foreach($class->sectionClassStudents as $sectionClassStudent)
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{$sectionClassStudent->student->admission_no}}</td>
                    <td>{{$sectionClassStudent->student->profile_code}}</td>
                    <td>{{$sectionClassStudent->student->name}}</td>
                    <td>{{$sectionClassStudent->student->lga->name ?? ''}}</td>
                    <td>{{$sectionClassStudent->student->lga->state->name ?? ''}}</td>
                    <td>
                    @if(!$sectionClassStudent->student->studentType)
                    <div class="alert alert-warning">Pls contact school to update your child information</div>
                    @elseif($invoice = $sectionClassStudent->currentStudentTerm()->invoice)
                        @if($invoice->status == 'pending')
                            <a href="{{route('payment.invoice', [$sectionClassStudent->id])}}"><button class="btn btn-sm btn-danger">Procede to Payment</button></a>
                        @else
                            <a href="{{route('payment.receipt', [$invoice->id])}}"><button class="btn btn-sm btn-success"><i class="fas fa-print"></i> Receipt</button></a>
                        @endif
                    @else
                        <a href="{{route('payment.generate.invoice', [$sectionClassStudent->id])}}"><button class="btn btn-sm btn-primary">Generate Invoice</button></a>
                    @endif
                    </td>
                </tr>
               
            @endforeach
            </tbody>
        </table>
    @endsection
</x-guest-layout>
