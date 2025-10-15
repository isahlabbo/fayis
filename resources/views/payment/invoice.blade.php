@extends('layouts.guest')
    @section('title')
        {{$sectionClassStudent->student->profile_code}}  e-payment invoices
    @endsection
    @section('styles')
    <style>
        
    </style>
    @endsection
    
    @section('content')
        <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-6">
            @foreach($sectionClassStudent->sectionClassStudentTerms as $sectionClassStudentTerm)
            @if($invoice = $sectionClassStudentTerm->invoice)
            <div class="car-body shadow p-4">
            <h5 class="text text-primary center m-4">Invoice {{$invoice->number}}</h5>
            <table class="table table-sm">
                <tr>
                    <td><b>Student Name:</b></td>
                    <td>{{$invoice->sectionClassStudentTerm->sectionClassStudent->student->name}}</td>
                </tr>
                <tr>
                    <td><b>Student Type:</b></td>
                    <td>{{$invoice->sectionClassStudentTerm->sectionClassStudent->student->studentType->name}}</td>
                </tr>
                <tr>
                    <td><b>Class:</b></td>
                    <td>{{$invoice->sectionClassStudentTerm->sectionClassStudent->sectionClass->name}}</td>
                </tr>
                <tr>
                    <td><b>Gender:</b></td>
                    <td>{{$invoice->sectionClassStudentTerm->sectionClassStudent->student->gender->name}}</td>
                </tr>
                <tr>
                    <td><b>Term:</b></td>
                    <td>{{$invoice->sectionClassStudentTerm->academicSessionTerm->term->name}}</td>
                </tr>
                <tr>
                    <td><b>Description:</b></td>
                    <td>{{$invoice->title}}</td>
                </tr>
                <tr>
                    <td><b>Amount:</b></td>
                    <td>{{number_format($invoice->amount,2)}}</td>
                </tr>
                <tr>
                    <td><b>Charges:</b></td>
                    <td>{{number_format($invoice->charges(),2)}}</td>
                </tr>
            </table>
            @if($invoice->status != 'paid')
            <button class="btn btn-primary" data-toggle="modal" data-target="#invoice_{{$invoice->id}}">Proceed to Payment</button>
                @include('payment.pay')    
            @endif    
            </div>
           @endif
           @endforeach
        </div>
        </div>
    @endsection
   @section('scripts')
   
@endsection
