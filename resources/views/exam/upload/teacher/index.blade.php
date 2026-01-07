@extends('layouts.app')
@section('title')
    Teacher Result Upload Report - {{ $teacher->user->name ?? '' }}
@endsection
@section('breadcrumb')  
@endsection 
@section('content')
@php 
    $teacherUploads = $teacher->resultUploadReport();   
    $notUploaded = count($teacherUploads['not_uploaded']);
    $submittedToClassMaster = count($teacherUploads['submitted_to_class_master']);  
    $submittedToExamOffice = count($teacherUploads['submitted_to_exam_office']);  
    $published = count($teacherUploads['published']);
    $inProgress = count($teacherUploads['in_progress']);    
    $allocated = count($teacherUploads['allocated']);
@endphp
<div class="row mt-4">
    <h5 class="text text-center mb-4">Result Upload Report for {{ $teacher->user->name ?? '' }}</h5>
    <table class="table table-sm table-bordered">
        <thead>
            <tr>
                <th>S/N</th>
                <th>Class</th>
                <th>Subject</th>
                <th>Number of Students</th>
                <th>Status</th>
                <th></th>
            </tr>
        </thead>
        <tbody> 
            @foreach($teacherUploads['allocated'] as $allocation) 
            @if(!$allocation->sectionClassSubject)
                @continue
            @endif         
            @php 
            $upload = $allocation->currentTermUpload();
            @endphp
            <tr class="">
                <td>{{ $loop->iteration }}</td>
                <td>{{ $allocation->sectionClassSubject->sectionClass->name }}</td>
                <td>{{ $allocation->sectionClassSubject->subject->name }}</td>
                <td>{{ $upload->studentResults()->count() }}</td>
                <td>
                    @switch($upload->status ?? -1)
                        @case('-1')
                            The Result has not been attempted
                            @break
                        @case('0')
                            The Result is with Subject Teacher
                            @break
                        @case('1')
                            The Result is with Class Master
                            @break
                        @case('2')
                            The Result is in Exam Office
                            @break
                        @case('3')
                            Result Published
                            @break
                        @default
                            Unknown Status Status
                    @endswitch
                    
                </td>
                <td>
                    <a href="#" data-toggle="modal" data-target="#edit_{{ $upload->id }}" class="btn btn-sm btn-outline-secondary"><i class="fas fa-edit"></i> Edit</a>
                </td>  
                @include('exam.upload.teacher.edit', ['upload' => $upload])             
            </tr>  
            @endforeach         
        </tbody>
    </table>    
</div>
@endsection