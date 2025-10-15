<x-app-layout>
    @section('title')
        {{config('app.name')}} students
    @endsection

@section('content')
<!--offer start  -->
<div class="row">
    <div class="col-md-2"></div>
    <div class="col-md-1"><button class="btn btn-secondary btn-block" id="print" onclick="printContent('printContent');" >Print</button></div>
</div><br>
<div id="printContent" class="">
<div class="offer" style="page-break-inside: avoid;">
    <div class="row">
    <div class="col-md-1"></div>
    <div class="col-md-10">
        <!-- address start -->
        <div class="row">
            <div class="col-md-8 text text-left" style="line-height: 2.5;">
            <img src="{{asset('images/instituteLogo.JPG')}}" alt=""><br>
                <h5 class="text" style="line-height: 2.5;">{{config('app.title')}}</h5>
                <h5 class="text" style="line-height: 2.5;">{{config('app.address')}}</h5>
                <h5 class="text" style="line-height: 2.5;">{{$student->sectionClass->section->name}} SECTION</h5>
                <h5 class="text" style="line-height: 2.5;">{{config('app.email')}}</h5>
                <h5 class="text" style="line-height: 2.5;">{{config('app.contact')}}</h5>
                <h5 class="text" style="line-height: 2.5;">{{date('d M, Y')}}.</h5>
            </div>
            <div class="col-md-4 text" style="line-spacing: 2;">
            <img src="{{asset(config('app.logo'))}}">
            </div>
        </div>
        <!-- address end -->
        
        <div class="col-md-12">
            <br>
            <br>
            <br>
        </div>
        <u><h5 class="text text-center"><b>{{$student->admissionLetter()->heading}} {{$student->sectionClass->name}} {{$student->sectionClass->sectionClassGroup->name}}</b></h5></u>
        <br>
        <p style="color:black;">Dear; {{$student->name}}'s Guardian</p>
        <p style="text-align: justify; line-height: 2.5; color: black;">
        {{$student->admissionLetter()->introduction_start}}
            <b><i>{{$student->activeSectionClass()->name}}</i></b> 
            {{$student->admissionLetter()->introduction_contenue}} {{$student->sectionClass->duration}} {{$student->admissionLetter()->introduction_end}} <b><i>{{$student->admission_no}}</i></b>
        </p>
        
        <p style="text-align: justify; line-height: 2.5; color: black;">
        
            <b>Note,</b> {{$student->admissionLetter()->payment_note_start}} <b><i>#{{$student->sectionClass->totalFee($student->currentSessionTerm()->academicSessionTerm->term,$student->gender)}}</i></b> {{$student->admissionLetter()->payment_note_contenue}} <b>#{{$student->sectionClass->totalFee($student->currentSessionTerm()->academicSessionTerm->term,$student->gender)/2}}</b> {{$student->admissionLetter()->payment_note_end}}
        </p>
        
        <p style="text-align: justify; line-height: 2.5; color: black;">
            <b>Congratulation</b> {{$student->admissionLetter()->congratulatory_note}}
        </p>
        <div class="col-md-12">
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
        </div>
        <div id="sign" class="text text-center">
            <p style="text-align: center; line-height: 2.5; color: black;">SIGN:</p>
            <br>
            <p style="text-align: center; color: black;">{{$student->sectionClass->section->name}} SECTION HEAD TEACHER</h4></p>
        </div>
        
    </div>
    </div>
</div>

@endsection
</x-app-layout>