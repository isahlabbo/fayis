@extends('layouts.app')
@section('title')
    Teachers result upload report
@endsection
@section('breadcrumb')  
@endsection
@section('content')
@php 
    $notAttempted = 0;
    $submittedToClassMaster = 0;
    $submittedToExamOffice = 0;
    $published = 0;
    $inProgress = 0;    
    $allocated = 0;
@endphp
<div class="row mt-4">
    <h5 class="text text-center mb-4">Teachers Result Upload Report</h5>
    <table class="table table-sm table-bordered">
        <thead>
            <tr>
                <th>S/N</th>
                <th>Teacher Name</th>
                <th>Teacher Phone Number</th>
                <th>Subject Allocations</th>
                <th>Submitted</th>
                <th>In Progress</th>
                <th>Not Attempted</th>
                <th>Submitted to Class Master</th>
                <th>Submitted to Exam Office</th>
                <th>Published</th>
            </tr>
        </thead>
        <tbody>
            @php 
                $allocated = 0;
                $notAttempted = 0;
                $submittedToClassMaster = 0;
                $submittedToExamOffice = 0;
                $published = 0;
                $inProgress = 0;
                $remark = '';
            @endphp
        @foreach(App\Models\Teacher::all() as $teacher)
        
        
            <tr class="">
                <td>{{ $loop->iteration }}</td>
                <td>{{ $teacher->user->name ?? '' }}</td>
                <td>{{ $teacher->phone ?? '' }}</td>
                <td>{{ $teacher->allocated() }}</td>
                <td>{{ $teacher->submitted() }}</td>
                <td>{{ $teacher->inProgress() }}</td>
                <td>{{ $teacher->notAttempted() }}</td>
                <td>{{ $teacher->submittedToClassMaster() }}</td>
                <td>{{ $teacher->submittedToExamOffice() }}</td>
                <td>{{ $teacher->published() }}</td>
                <td>{{ $teacher->uploadRemark() }}
                    <a href="{{ route('exam.upload.teacher.index', $teacher->id) }}" class="btn btn-sm btn-outline-secondary"><i class="fas fa-edit"></i></a>
                </td>
            </tr>
            @php 
                $allocated += $teacher->allocated();
                $notAttempted += $teacher->notAttempted();
                $submittedToClassMaster += $teacher->submittedToClassMaster();
                $submittedToExamOffice += $teacher->submittedToExamOffice();
                $published += $teacher->published();
                $inProgress += $teacher->inProgress();
            @endphp
        @endforeach
            <tr >
                <td colspan="3"><b>Total</b></td>
                <td><b>{{ $allocated }}</b></td>
                <td><b>{{ $submittedToClassMaster }}</b></td>
                <td><b>{{ $inProgress }}</b></td>
                <td><b>{{ $notAttempted }}</b></td>
                <td><b>{{ $submittedToClassMaster  }}</b></td>
                <td><b>{{ $submittedToExamOffice}}</b></td>
                <td><b>{{ $published }}</b></td>
                <td>
                    @if($notAttempted + $inProgress > 0)
                        <span class="badge bg-secondary lg">{{$inProgress + $notAttempted}} Pending Uploads</span> 
                    @else
                        <span class="badge bg-success">All Results Uploaded</span>  
                    @endif
                </td>
            </tr>
        </tbody>
    </table>
</div>
@endsection