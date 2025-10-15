<x-app-layout>
    @section('title')
        {{config('app.name')}} {{$sectionClassStudent->number}} receipt
    @endsection
   
    @section('content')
    <div class="row">
        <div class="col-sm-2"></div>
        <div class="col-sm-2"><button class="btn btn-secondary btn-block" id="print" onclick="printContent('report');" >Print</button></div>
    </div>
    <div class=" container" id="report">
        @foreach($sectionClassStudent->sectionClassStudentTerms as $sectionClassStudentTerm)
        @if($sectionClassStudentTerm->invoice && $sectionClassStudentTerm->invoice->status=='paid')
        @for($i=1; $i<=2; $i++)
        <div class="receipt" style="page-break-inside: avoid;">
            <div class="head">
                <div class="row">
                        <div class="col-sm-2 center"><img src="{{asset('assets/images/logo.png')}}" alt="" width="85" height="85" ></div>
                        <div class="col-sm-8 center">
                            <h4>SULTAN MUHAMMADU MACCIDO INSTITUTE FOR QUR'AN & GENERAL STUDUES, SOKOTO</h4>
                            <h6>Sheikh Abdulrahman Al-Sudaus Road, Sokoto</h6>
                            <i>Website: www.smmiqgs.org E-mail smmiqgssokoto@gmail.com GSM 07046434623, 09023080733</i>
                            <b>{{$sectionClassStudentTerm->invoice->academicSession->name}}</b>
                        </div>
                        <div class="col-sm-2 center">
                        {{$sectionClassStudentTerm->invoice->generateQrCode('SMMIQGS E-Payment has successfully capture the payment of '. $sectionClassStudentTerm->invoice->number )}}
                        <b>{{$sectionClassStudentTerm->invoice->number}}</b>
                        </div>
                </div>
            </div>
            <table class="table p-4 mt-4 table-striped">
                <tr>
                    <td>Name</td>
                    <td>{{$sectionClassStudentTerm->invoice->sectionClassStudentTerm->sectionClassStudent->student->name}}</td>
                </tr>
                <tr>
                    <td>Admission No</td>
                    <td>{{$sectionClassStudentTerm->invoice->sectionClassStudentTerm->sectionClassStudent->student->admission_no}}</td>
                </tr>
                <tr>
                    <td>Class</td>
                    <td>{{$sectionClassStudentTerm->invoice->sectionClassStudentTerm->sectionClassStudent->sectionClass->name}}</td>
                </tr>
                <tr>
                    <td>Amount Paid</td>
                    <td><i class="fa-solid fa-naira-sign"></i><b> {{$sectionClassStudentTerm->invoice->amount}}</b></td>
                </tr>
                <tr>
                    <td>ID</td>
                    <td><i class="fa-solid fa-naira-sign"></i><b> {{sprintf('%04d',Auth::user()->id)}}</b></td>
                </tr>
                <tr>
                    <td>Transaction ID</td>
                    <td><i class="fa-solid fa-naira-sign"></i><b> {{$sectionClassStudentTerm->invoice->transaction->transaction_id ?? ''}}</b></td>
                </tr>
                <tr>
                    <td>Term</td>
                    <td><b> {{$sectionClassStudentTerm->invoice->sectionClassStudentTerm->academicSessionTerm->term->name}}</b></td>
                </tr>
            
            </table>
            <p> Sign: ___________________________ Date: {{$sectionClassStudentTerm->invoice->created_at}}</p>
        </div> 
        <br>
        <br>
        <br>
        <br>
        <hr style="border: 2px dashed black;">
        @endfor
        @endif
        @endforeach
    </div>
    @endsection
</x-app-layout>