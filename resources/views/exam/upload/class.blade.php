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

    <h3 class="text mb-4">Classes Result Upload Report</h3>
    @foreach(App\Models\Section::all() as $section)
<div class="col-md-12">
<h4>{{ $section->name }} Reports</h4>
</div>
    
    <table class="table table-sm table-bordered">
        
        <thead>
            <tr>
                <th>S/N</th>
                <th>Class Name</th>
                <th>Class Master Name</th>
                <th>Class Master Phone</th>
                <th>Number of Student</th>
                <th>Number of Subject</th>
                <th>Submitted</th>
                <th>In Progress</th>
                <th>Not Attempted</th>
                <th>Submitted to Class Master</th>
                <th>Submitted to Exam Office</th>
                <th>Published</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        @foreach($section->sectionClasses as $sectionClass)
            <tr class="">
                <td>{{ $loop->iteration }}</td>
                <td>{{ $sectionClass->name ?? '' }}</td>
                <td>{{ $sectionClass->currentClassMaster()->teacher->user->name ?? '' }}</td>
                <td>{{ $sectionClass->currentClassMaster()->teacher->phone ?? '' }}</td>
                <td>{{ $sectionClass->studentCounts() }}</td>
                <td>{{ $sectionClass->subjects() }}</td>
                <td>{{ $sectionClass->submitted() }}</td>
                <td>{{ $sectionClass->inProgress()}}</td>
                <td>{{ $sectionClass->notAttempted() }}</td>
                <td>{{ $sectionClass->submittedToClassMaster()}}</td>
                <td>{{ $sectionClass->submittedToExamOffice()}}</td>
                <td>{{ $sectionClass->published() }}</td>
                <td>{{ $sectionClass->uploadRemark() }} </td>
                <td><a href="{{route('exam.upload.class.report.show',[$sectionClass->id])}}" class="btn btn-sm btn-outline-primary"><i class="fas fa-eye"></i></a></td>
            </tr>
        @endforeach

            <tr >
                <td colspan="4"><b>Total</b></td>
                <td><b>{{ $section->studentCounts() }}</b></td>
                <td><b>{{ $section->subjects() }}</b></td>
                <td><b>{{ $section->submitted() }}</b></td>
                <td><b>{{ $section->inProgress()}}</b></td>
                <td><b>{{ $section->notAttempted() }}</b></td>
                <td><b>{{ $section->submittedToClassMaster()}}</b></td>
                <td><b>{{ $section->submittedToExamOffice()}}</b></td>
                <td><b>{{ $section->published() }}</b></td>
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