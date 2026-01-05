@extends('layouts.app')
@section('title')
    Teachers result upload report
@endsection
@section('breadcrumb')  
@endsection
@section('content')
<div class="row mt-4">
    <table class="table table-sm table-bordered">
        <thead>
            <tr>
                <th>S/N</th>
        
                <th>Teacher Name</th>
                <th>Allocated Subjects</th>
                <th>Not Uploaded</th>
                <th>In Progress</th>
                <th>Submitted to Class Master</th>
                <th>Submitted to Exam Office</th>
                <th>Published</th>
            </tr>
        </thead>
        <tbody>
        @foreach(App\Models\Teacher::all() as $teacher)
        @php 
        $teacherUploads = $teacher->resultUploadReport();
        @endphp
        @if(count($teacherUploads['allocated']) > 0)
            <tr class="{{ $teacherUploads['table_row_class'] }}">
                <td>{{ $loop->iteration }}</td>
                <td>{{ $teacher->user->name ?? '' }}</td>
                <td>{{ count($teacherUploads['allocated']) }}</td>
                <td>{{ count($teacherUploads['not_uploaded']) }}</td>
                <td>{{ count($teacherUploads['in_progress']) }}</td>
                <td>{{ count($teacherUploads['submitted_to_class_master']) }}</td>
                <td>{{ count($teacherUploads['submitted_to_exam_office']) }}</td>
                <td>{{ count($teacherUploads['published']) }}</td>
                <td>{{ $teacherUploads['remark'] }}</td>
            </tr>
        @endif
        @endforeach
        </tbody>
    </table>
</div>
@endsection