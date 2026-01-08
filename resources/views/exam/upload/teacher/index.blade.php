@extends('layouts.app')
@section('title')
    Teacher Result Upload Report - {{ $teacher->user->name ?? '' }}
@endsection
@section('breadcrumb')  
@endsection 
@section('content')
@php 
    $teacherUploads = $teacher->resultUploadReport();   
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
            @php 
            $upload = $allocation->currentTermUpload();
            @endphp
         
            <tr class="">
                <td>{{ $loop->iteration }}</td>
                <td>{{ $allocation->sectionClassSubject->sectionClass->name ?? ''}}</td>
                <td>{{ $allocation->sectionClassSubject->subject->name ?? ''}}</td>
                <td>{{ $upload ? $upload->studentResults()->count()/3 : 0 }}</td>
                <td>
                    
                    @switch($allocation->currentTermUploadStatus())
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
                @if(!$upload)
                <td></td>
                @else
                <td>
                    <a href="#" data-toggle="modal" data-target="#edit_{{ $upload->id }}" class="btn btn-sm btn-outline-secondary"><i class="fas fa-edit"></i> Edit</a>
                </td>  
                @include('exam.upload.teacher.edit', ['upload' => $upload]) 
                @endif            
            </tr>  
           
            @endforeach         
        </tbody>
    </table>    
</div>
@endsection