@extends('layouts.app')
@section('title')
    Teachers result upload report
@endsection
@section('breadcrumb')  
@endsection
@section('content')
@php 
    $notUploaded = 0;
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
        @foreach(App\Models\Teacher::all() as $teacher)
        @php 
        $teacherUploads = $teacher->resultUploadReport();
        $notUploaded += count($teacherUploads['not_uploaded']);
        $submittedToClassMaster += count($teacherUploads['submitted_to_class_master']);
        $submittedToExamOffice += count($teacherUploads['submitted_to_exam_office']);
        $published += count($teacherUploads['published']);  
        $inProgress += count($teacherUploads['in_progress']);
        $allocated += count($teacherUploads['allocated']);
        @endphp
        @if(count($teacherUploads['allocated']) > 0)
            <tr class="">
                <td>{{ $loop->iteration }}</td>
                <td>{{ $teacher->user->name ?? '' }}</td>
                <td>{{ $teacher->phone ?? '' }}</td>
                <td>{{ count($teacherUploads['allocated']) }}</td>
                <td>{{ count($teacherUploads['submitted_to_class_master']) }}</td>
                <td>{{ count($teacherUploads['in_progress']) }}</td>
                <td>{{ count($teacherUploads['not_attempted']) }}</td>
                <td>{{ count($teacherUploads['submitted_to_class_master'])+count($teacherUploads['submitted_to_exam_office'])+  count($teacherUploads['published'])}}</td>
                <td>{{ count($teacherUploads['submitted_to_exam_office']) + count($teacherUploads['published'])}}</td>
                <td>{{ count($teacherUploads['published']) }}</td>
                <td>{{ $teacherUploads['remark'] }}
                    <a href="{{ route('exam.upload.teacher.index', $teacher->id) }}" class="btn btn-sm btn-outline-secondary"><i class="fas fa-edit"></i></a>
                </td>
            </tr>
        @endif
        @endforeach
            <tr >
                <td colspan="3"><b>Total</b></td>
                <td><b>{{ $allocated }}</b></td>
                <td><b>{{ $submittedToClassMaster }}</b></td>
                <td><b>{{ $inProgress }}</b></td>
                <td><b>{{ $not_attempted }}</b></td>
                <td><b>{{ $submittedToClassMaster + $submittedToExamOffice + $published }}</b></td>
                <td><b>{{ $submittedToExamOffice + $published }}</b></td>
                <td><b>{{ $published }}</b></td>
                <td>
                    @if($not_attempted + $inProgress > 0)
                        <span class="badge bg-secondary lg">{{$inProgress + $not_attempted}} Pending Uploads</span> 
                    @else
                        <span class="badge bg-success">All Results Uploaded</span>  
                    @endif
                </td>
            </tr>
        </tbody>
    </table>
</div>
@endsection