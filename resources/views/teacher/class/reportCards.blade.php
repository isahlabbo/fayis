@extends('Layouts.app')

@section('title')
    {{$sectionClass->name}} Report Cards    
@endsection
@section('breadcrumb')
@endsection

@section('content')
    <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-2"><button class="btn btn-secondary btn-block" id="print" onclick="printContent('report');" >Print</button></div>
        </div>
        <div id="report">
        @foreach($sectionClass->sectionClassStudents->where('status','Active') as $sectionClassStudent)
            @foreach($sectionClassStudent->sectionClassStudentTerms->where('status','Active') as $sectionClassStudentTerm)<br><br>
                @php
                    $student = $sectionClassStudent->student;
                @endphp 
                   
                @if(!$sectionClassStudentTerm->sectionClassStudentTermResultPublish) 
                    <div class="alert alert-warning">The  <b><em>{{$sectionClassStudentTerm->AcademicSessionTerm->term->name}} </b></em> result of  <b><em>{{$student->name}} </b></em> in <b><em>{{$sectionClassStudent->sectionClass->name}} </b></em> is not available for view, please check back next time</div>
                @else
                    @include('section.class.student.result.reportcard.view')
                @endif
            @endforeach
        @endforeach
    </div>
@endsection