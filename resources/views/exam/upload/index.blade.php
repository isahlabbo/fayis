@extends('layouts.app')

@section('title')
    {{$section->name}} upload
@endsection

@section('content')
<div class="row">
    @foreach($section->sectionClasses as $sectionClass)
    @php
    $totalSubjects = count($sectionClass->sectionClassSubjects);
    $uploaded = count($sectionClass->subjectResultUploads()['uploaded']);
    $remaining = $totalSubjects - $uploaded;
    @endphp
    <div class="col-md-3 mb-4">
            <div class="card-body shadow text-center rounded-3">
                <h6 class="text-primary">
                    <i class="fas fa-home"></i> {{$sectionClass->name}}
                </h6>
                <a href="" class="text-decoration-none"> <p class="">Subjects: {{count($sectionClass->sectionClassSubjects)}}</p></a>
                <p class="">Uploaded: {{$uploaded}}</p>
                <p class="">Remaining: {{$remaining}}</p>
                @if($uploaded > 0)
                <a href="{{route('exam.upload.summary',[$sectionClass->id])}}"class="btn btn-sm btn-info">View Result</a>
                @endif
            </div>
        </div>
    @endforeach
</div>
@endsection