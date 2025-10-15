<x-app-layout>
    @section('title')
        {{$sectionClass->name}} fees
    @endsection
    
    @section('content')
        <h6 class="text text-primary m-4">{{$sectionClass->currentSession()->name}} payments for {{$sectionClass->name}}</h6>
        <table class="table table-striped table-sm" id="myTable">
            <thead>
                <tr>
                    <th>STUDENT</th>
                    <th>DESCRIPTION</th>
                    <th>MODE OF PAYMENT</th>
                    <th>REGISTERED BY</th>
                    <th>AMOUNT</th>
                    <th>TERM</th>
                    <th>DATE </th>
                    <th><button data-toggle="modal" data-target="#addPayment" class="btn btn-sm btn-outline-primary">Add Payment</button></th>
                    @include('finance.payments.add')
                </tr>
            </thead>
            <tbody>
            @foreach($sectionClass->sectionClassFees as $sectionClassFee)
            @foreach($sectionClassFee->payments as $payment)
                @include('finance.payments.edit')
                <tr>
                    <td>{{$payment->sectionClassStudent->student->name}}</td>
                    <td>{{$payment->sectionClassFee->fee->name}}</td>
                    <td>{{$payment->mode}}</td>
                    <td>{{$payment->user->name}}</td>
                    <td>{{$payment->amount}}</td>
                    <td>{{$payment->term->name}}</td>
                    <td>{{$payment->date}}</td>
                    <td>
                        <button data-toggle="modal" data-target="#edit_{{$payment->id}}" class="btn btn-sm btn-outline-secondary"><i class="fas fa-pen"></i></button>
                        <a href="{{route('finance.payments.delete', [$payment->id])}}" onclick="return confirm('Are you sure you want delete this payment?')" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></a>
                        <a href="{{route('finance.payments.receipt', [$payment->id])}}" class="btn btn-sm btn-outline-success"><i class="fas fa-receipt"></i></a>
                    </td>
                </tr>
                
            @endforeach
            @endforeach
            </tbody>
        </table>
    @endsection
</x-app-layout>
