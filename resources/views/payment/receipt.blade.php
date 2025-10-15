@extends('layouts.guest')
    @section('title')
        {{config('app.name')}} {{$invoice->number}} receipt
    @endsection
   @php
   $invoice->verifyPayment();
   @endphp
    @section('content')
    <div class="row">
        <div class="col-sm-2"></div>
        <div class="col-sm-2"><button class="btn btn-secondary btn-block" id="print" onclick="printContent('report');" >Print</button></div>
    </div>
    <div class=" container" id="report">
        
        @for($i=1; $i<=2; $i++)
        <div class="receipt" style="page-break-inside: avoid;">
            <div class="head">
                <div class="row">
                        <div class="col-sm-2 center"><img src="{{asset('assets/images/logo.png')}}" alt="" width="85" height="85" ></div>
                        <div class="col-sm-8 center">
                            <h4>SULTAN MUHAMMADU MACCIDO INSTITUTE FOR QUR'AN & GENERAL STUDUES, SOKOTO</h4>
                            <h6>Sheikh Abdulrahman Al-Sudais Road, Sokoto</h6>
                            <i>Website: www.smmiqgs.org E-mail smmiqgssokoto@gmail.com GSM 07046434623, 09023080733</i>
                            <b>{{$invoice->academicSession->name}}</b>
                        </div>
                        <div class="col-sm-2 center">
                        {{$invoice->generateQrCode('SMMIQGS E-Payment has successfully capture the payment of '. $invoice->number )}}
                        <b>{{$invoice->number}}</b>
                        </div>
                </div>
            </div>
            <table class="table p-4 mt-4 table-stripped">
                <tr>
                    <td>Name</td>
                    <td>{{$invoice->sectionClassStudentTerm->sectionClassStudent->student->name}}</td>
                </tr>
                <tr>
                    <td>Admission No</td>
                    <td>{{$invoice->sectionClassStudentTerm->sectionClassStudent->student->admission_no}}</td>
                </tr>
                <tr>
                    <td>Class</td>
                    <td>{{$invoice->sectionClassStudentTerm->sectionClassStudent->sectionClass->name}}</td>
                </tr>
                <tr>
                    <td>Payment Type</td>
                    <td><i class="fa-solid fa-naira-sign"></i><b>School Fees</b></td>
                </tr>
                <tr>
                    <td>Amount Paid</td>
                    <td><i class="fa-solid fa-naira-sign"></i><b> 
                    @if($invoice->status == 'paid')
                    {{$invoice->transactions->amount ?? $invoice->amount}}
                    @else
                    'Transaction was not captured'
                    @endif
                    </b></td>
                </tr>
                
                <tr>
                    <td>Transaction ID</td>
                    <td><i class="fa-solid fa-naira-sign"></i><b> {{$invoice->transaction->transaction_id ?? ''}}</b></td>
                </tr>
                <tr>
                    <td>Term</td>
                    <td><b> {{$invoice->sectionClassStudentTerm->academicSessionTerm->term->name}}</b></td>
                </tr>
            
            </table>
            <p> Sign: ___________________________ Date: {{$invoice->created_at}}</p>
        </div> 
        <br>
        <br>
        <hr style="border: 2px dashed black;">
        @endfor
        
    </div>
    @endsection
