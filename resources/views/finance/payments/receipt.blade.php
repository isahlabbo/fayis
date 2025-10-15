@extends('layouts.guest')
    @section('title')
        {{config('app.name')}} {{$payment->id}} receipt
    @endsection
   
    @section('content')
    <div class="row">
        <div class="col-sm-2"></div>
        <div class="col-sm-2">
            <button class="btn btn-secondary btn-block" id="print" onclick="printContent('report');" >Print</button>
        <br>
        </div>
    <br>
    </div>
    <div class="container" id="report">
        @for($i=1; $i<=3; $i++)
        <div class="receipt" style="page-break-inside: avoid;">
            <div class="head">
                <div class="row">
                        <div class="col-sm-2 text-right"><img src="{{asset('images/logo.jpg')}}" alt="" width="120"></div>
                        <div class="col-sm-8 text text-center">
                            <h3 class="text text-primary">FATIMA YAHAYA INTERNATIONAL SCHOOL, SOKOTO</h3>
                            <h5 class="text text-secondary">No 2. Birnin Kebbi Road, Sifawa, Bodinga LG, Sokoto State</h5>
                            <h6><i>Website: www.fayis.ng E-mail info@fayis.ng GSM 09066878547, 07037370625</i></h6>
                            <p><b>{{$payment->academicSession->name}} Academic Session</b></p>
                        </div>
                        <div class="col-sm-2 text text-left">
                        {{$payment->generateQrCode('The payment of '.$payment->sectionClassStudent->student->name.' for '.$payment->term->name.' is recorded successfully at '.$payment->date, 120)}}
                        <p><b>Scan to Verify</b></p>
                        </div>
                </div>
            </div>
            <table class="table table-sm table-stripped p-4 mt-4 table-stripped">
                <tr>
                    <td><b>Name</b></td>
                    <td>{{$payment->sectionClassStudent->student->name}}</td>
                </tr>
                <tr>
                    <td><b>Admission No</b></td>
                    <td>{{$payment->sectionClassStudent->student->admission_no}}</td>
                </tr>
                <tr>
                    <td><b>Class</b></td>
                    <td>{{$payment->sectionClassStudent->sectionClass->name}}</td>
                </tr>
                <tr>
                    <td><b>Description</b></td>
                    <td>{{$payment->sectionClassFee->fee->name}}</b></td>
                </tr>
                <tr>
                    <td><b>Amount Paid</b></td>
                    <td>
                   {{number_format($payment->amount,2)}}
                    </td>
                </tr>
                <tr>
                    <td><b>Recorded By</b></td>
                    <td>
                   {{$payment->user->name}}
                    </td>
                </tr>
                
                <tr>
                    <td><b>Transaction ID</b></td>
                    <td>{{$payment->id}}</td>
                </tr>
                <tr>
                    <td><b>Session Term</b></td>
                    <td>{{$payment->term->name}}</td>
                </tr>
            
            </table>
            <p> Sign: ___________________________ Date: {{$payment->date}}</p>
        </div> 
        <br>
        <br>
        @endfor
    </div>
    @endsection
