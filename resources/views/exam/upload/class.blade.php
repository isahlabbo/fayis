@extends('layouts.app')
@section('title')
    Teachers result upload report
@endsection
@section('breadcrumb')  
@endsection
@section('content')
@php 
    
@endphp
<div class="row mt-4">

    <h5 class="text text-center mb-4">Classes Result Upload Report</h5>
    @foreach(App\Models\Section::all() as $section)

    <h6 class="text text-center mb-4">{{ $section->name }}</h6>
    <table class="table table-sm table-bordered">
        <thead>
            <tr>
                <th>S/N</th>
                <th>Class Name</th>
                <th>Number of Student</th>
                <th>Number of Subject</th>
                <th>Submitted</th>
                <th>In Progress</th>
                <th>Not Attempted</th>
                <th>Submitted to Class Master</th>
                <th>Submitted to Exam Office</th>
                <th>Published</th>
            </tr>
        </thead>
        <tbody>
        @foreach($section->sectionClasses as $sectionClass)
            <tr class="">
                <td>{{ $loop->iteration }}</td>
                <td>{{ $sectionClass->name ?? '' }}</td>
                <td>{{ $sectionClass->studentCounts() }}</td>
                <td>{{ $sectionClass->subjects() }}</td>
                <td>{{ $sectionClass->submitted() }}</td>
                <td>{{ $sectionClass->inProgress()}}</td>
                <td>{{ $sectionClass->notAttempted() }}</td>
                <td>{{ $sectionClass->submittedToClassMaster()}}</td>
                <td>{{ $sectionClass->submittedToExamOffice()}}</td>
                <td>{{ $sectionClass->published() }}</td>
                <td>{{ $sectionClass->uploadRemark() }}</td>
            </tr>
        @endforeach

            <tr >
                <td colspan="2"><b>Total</b></td>
                <td>{{ $section->studentCounts() }}</td>
                <td>{{ $section->subjects() }}</td>
                <td>{{ $section->submitted() }}</td>
                <td>{{ $section->inProgress()}}</td>
                <td>{{ $section->notAttempted() }}</td>
                <td>{{ $section->submittedToClassMaster()}}</td>
                <td>{{ $section->submittedToExamOffice()}}</td>
                <td>{{ $section->published() }}</td>
                <td>
                    @if($section->notAttempted() + $section->InProgress() > 0)
                        <span class="badge bg-secondary lg">{{$section->inProgress() + $section->notAttempted()}} Pending Uploads</span> 
                    @else
                        <span class="badge bg-success">All Results Uploaded</span>  
                    @endif
                </td>
            </tr>
        </tbody>
    </table>
    @endforeach
</div>
@endsection