<x-app-layout>
    @section('title')
        {{config('app.name')}} payment
    @endsection
    @section('breadcrumb')
       {{Breadcrumbs::render('dashboard')}}
    @endsection
    @section('content')
    <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-1"><button class="btn btn-secondary btn-block" id="print" onclick="printContent('report');" >Print</button></div>
    </div><br>
    <div class="container" id="report" style="page-break-inside: avoid;">
    <div  style="border: 4px dashed black; border-radius: 30px; padding: 15px;">
        <div class="row">
            <div class="col-md-2 text-right">
                <img src="{{asset(config('app.logo'))}}">
            </div>
            <div class="col-md-10">
                <h4 class="text text-center">{{config('app.title')}}</h4>  
                <h4 class="text text-center">{{config('app.address')}}</h4>  
                <h4 class="text text-center">{{config('app.motto')}}</h4>
                <h4 class="text text-center">{{config('app.contact')}} / {{config('app.email')}}</h4>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-6">
                <table>
                    <tr>
                        <td><b>STUDENT NAME: </b></td>
                        <td>{{$sectionClassStudent->student->name}}</td>
                    </tr>
                    <tr>
                        <td><b>ADMISSION NO: </b></td>
                        <td>{{$sectionClassStudent->student->admission_no}}</td>
                    </tr>
                    <tr>
                        <td><b>CLASS: </b></td>
                        <td>{{$sectionClassStudent->sectionClass->name}} {{$sectionClassStudent->sectionClass->sectionClassGroup->name}}</td>
                    </tr>
                    <tr>
                        <td><b>TERM: </b></td>
                        <td>{{strtoupper($term->name)}}</td>
                    </tr>
                    
                    <tr>
                        <td><b>DATE: </b></td>
                        <td>{{date('d M, Y')}}.</td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6"></div>
        </div>
        <br>
        <div class="row">
        <div class="col-md-12">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Description</th>
                    <th>Amount (#)</th>
                    <th>Total (#)</th>
                </tr>
            </thead>
            <tbody>
            @foreach($sectionClassStudent->sectionClass->sectionClassPayments->where('term_id',$term->id) as $payment)
            
                @if($payment->gender_id == $sectionClassStudent->student->gender_id || $payment->gender_id == 3)
                <tr>
                    <td>{{$payment->name}}</td>
                    <td>{{$payment->amount}}</td>
                    <td>{{$payment->amount}}</td>
                </tr>
                @endif
            @endforeach
                <tr>
                    <td><b>Total</b></td>
                    <td></td>
                    <td><b>{{$sectionClassStudent->feeAmount($term)}}</b></td>
                </tr>
            </tbody>
        </table>
        </div>
        </div>
        <div class="row">
        <div class="col-md-12">
        <h3 class="text text-success">Terms and Conditions</h3>
        </div>
        <div class="col-md-12">
            <p style="color:black; line-height: 2.5;">The payment of <b>#{{$sectionClassStudent->paidAmount($term)}}</b> out of <b>#{{$sectionClassStudent->feeAmount($term)}}</b> has been recieved through <b>{{$sectionClassStudent->modeOfPayment($term)}}</b> 
             for the above scholar  as {{$sectionClassStudent->feeAmount($term, $sectionClassStudent->student->gender)-$sectionClassStudent->paidAmount($term,$sectionClassStudent->student->gender)>0 ? 'an Installment' : 'a Complete'}} payment.</p>
             @if($sectionClassStudent->feeAmount($term,$sectionClassStudent->student->gender)-$sectionClassStudent->paidAmount($term,$sectionClassStudent->student->gender)>0)
             <p style="color:black; line-height: 2.5;">Therefore the school is expecting the remaining payment of <b>{{$sectionClassStudent->feeAmount($term)-$sectionClassStudent->paidAmount($term)}}</b> sooner, thanks for the effort.</p>
            @else
            <p style="color:black; line-height: 2.5;">Congratulation for completing your child payment, best regard.</p>
             @endif
        </div>
        </div>  
    </div>
    <br>
    <div  style="border: 4px dashed black; border-radius: 30px; padding: 15px;">
        <div class="row">
            <div class="col-md-2 text-right">
                <img src="{{asset(config('app.logo'))}}">
            </div>
            <div class="col-md-10">
                <h4 class="text text-center">{{config('app.title')}}</h4>  
                <h4 class="text text-center">{{config('app.address')}}</h4>  
                <h4 class="text text-center">{{config('app.motto')}}</h4>
                <h4 class="text text-center">{{config('app.contact')}} / {{config('app.email')}}</h4>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-6">
                <table>
                    <tr>
                        <td><b>STUDENT NAME: </b></td>
                        <td>{{$sectionClassStudent->student->name}}</td>
                    </tr>
                    <tr>
                        <td><b>ADMISSION NO: </b></td>
                        <td>{{$sectionClassStudent->student->admission_no}}</td>
                    </tr>
                    <tr>
                        <td><b>CLASS: </b></td>
                        <td>{{$sectionClassStudent->sectionClass->name}} {{$sectionClassStudent->sectionClass->sectionClassGroup->name}}</td>
                    </tr>
                    <tr>
                        <td><b>TERM: </b></td>
                        <td>{{strtoupper($term->name)}}</td>
                    </tr>
                    
                    <tr>
                        <td><b>DATE: </b></td>
                        <td>{{date('d M, Y')}}.</td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6"></div>
        </div>
        <br>
        <div class="row">
        <div class="col-md-12">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Description</th>
                    <th>Amount (#)</th>
                    <th>Total (#)</th>
                </tr>
            </thead>
            <tbody>
            @foreach($sectionClassStudent->sectionClass->sectionClassPayments as $payment)
            
                @if($payment->gender_id == $sectionClassStudent->student->gender_id || $payment->gender_id == 3)
                <tr>
                    <td>{{$payment->name}}</td>
                    <td>{{$payment->amount}}</td>
                    <td>{{$payment->amount}}</td>
                </tr>
                @endif
            @endforeach
                <tr>
                    <td><b>Total</b></td>
                    <td></td>
                    <td><b>{{$sectionClassStudent->feeAmount($term)}}</b></td>
                </tr>
            </tbody>
        </table>
        </div>
        </div>
        <div class="row">
        <div class="col-md-12">
        <h3 class="text text-success">Terms and Conditions</h3>
        </div>
        <div class="col-md-12">
            <p style="color:black; line-height: 2.5;">The payment of <b>#{{$sectionClassStudent->paidAmount($term)}}</b> out of <b>#{{$sectionClassStudent->feeAmount($term)}}</b> has been recieved through <b>{{$sectionClassStudent->modeOfPayment($term)}}</b> 
             for the above scholar  as {{$sectionClassStudent->feeAmount($term, $sectionClassStudent->student->gender)-$sectionClassStudent->paidAmount($term,$sectionClassStudent->student->gender)>0 ? 'Installment' : 'Complete'}} payment.</p>
             @if($sectionClassStudent->feeAmount($term,$sectionClassStudent->student->gender)-$sectionClassStudent->paidAmount($term,$sectionClassStudent->student->gender)>0)
             <p style="color:black; line-height: 2.5;">Therefore the school is expecting the remaining payment of <b>{{$sectionClassStudent->feeAmount($term)-$sectionClassStudent->paidAmount($term)}}</b> sooner, thanks for the effort.</p>
            @else
            <p style="color:black; line-height: 2.5;">Congratulation for completing your child payment, best regard.</p>
             @endif
        </div>
        </div>  
    </div>
    </div>
    
    @endsection
    
</x-app-layout>
