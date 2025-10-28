@extends('layouts.app') 
    @section('title')
        {{strtolower($sectionClassSubjectTeacher->sectionClassSubject->subject->name)}} 
    @endsection
    @section('breadcrumb')
    
    @endsection
    @section('content')
        <!-- display card for First CA, Second CA and Exam with appropiate icon -->
    <h6></h6>
    <div class="row">
        @foreach($sectionClassSubjectTeacher->getThisSessionUploads() as $upload)
        @if($upload->term_id == $upload->currentSessionTerm()->term_id)
       <div class="col-md-12">
        <div class="progress" style="height: 35px; font-size:20px;">
            @php
            $level = $upload->getLevel();
            $percentage = ($level / 3) * 100;
            @endphp
            <div class="progress-bar" role="progressbar" style="width: {{$percentage}}%;" aria-valuenow="{{$percentage}}" aria-valuemin="0" aria-valuemax="100">{{$percentage}}%</div>
        </div>
        <div class="alert alert-success">{{$sectionClassSubjectTeacher->sectionClassSubject->sectionClass->name}}-{{$sectionClassSubjectTeacher->sectionClassSubject->subject->name}} for {{$upload->term->name}} Uploaded: {{$level}} / 3</div>
        </div>

        <div class="col-md-4 mb-4">
            <a href="{{route('teacher.subject.firstca.index',[$upload->id])}}" class="text-decoration-none">
                <div class="card-body shadow text-center rounded-3">
                    <h5 class="text-primary">
                        <i class="fas fa-pen"></i> First CA
                    </h5>
                    <h6 class="text-primary">
                        
                    </h6>
                </div>
            </a>
        </div>

        <div class="col-md-4 mb-4">
            <a href="{{route('teacher.subject.secondca.index',[$upload->id])}}" class="text-decoration-none">
                <div class="card-body shadow text-center rounded-3">
                    <h5 class="text-primary">
                        <i class="fas fa-pen"></i> Second CA
                    </h5>
                    <h6 class="text-primary">
                        
                    </h6>
                </div>
            </a>
        </div>
        <div class="col-md-4 mb-4">
            <a href="{{route('teacher.subject.exam.index',[$upload->id])}}" class="text-decoration-none">
                <div class="card-body shadow text-center rounded-3">
                    <h5 class="text-primary">
                        <i class="fas fa-pen"></i> Exam
                    </h5>
                    <h6 class="text-primary">
                        
                    </h6>
                </div>
            </a>    
        </div>
        @endif
        @endforeach
    </div>
    
    @endsection