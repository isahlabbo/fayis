@extends('layouts.app')
@section('title')
    {{$sectionClass->name}} - Class Subjects & Teachers
@endsection
@section('breadcrumb')

@endsection
@section('content')
<div class="row mt-4">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>S/N</th>
                <th>Subject</th>
                <th>Teacher Name</th>
                <th>Teacher Phone</th>
                <th>Result Status</th>
            </tr>
        </thead>
        <tbody>
        @foreach($sectionClass->sectionClassSubjects as $sectionClassSubject)
            @foreach($sectionClassSubject->sectionClassSubjectTeachers->where('status', 'Active') as $sectionClassSubjectTeacher)    
                <!-- use iteration of the parent loop -->

                <tr>
                    <td>{{ $loop->parent->iteration }}</td>
                    <td>{{ $sectionClassSubject->subject->name }}</td>
                    <td>{{ $sectionClassSubjectTeacher->teacher->user->name ?? ''}}</td>
                    <td>{{ $sectionClassSubjectTeacher->teacher->phone ?? ''}}</td>
                    <td>
                        @switch($sectionClassSubjectTeacher->currentTermUploadStatus())
                            @case(-1)
                                <span class="badge bg-secondary">Not Uploaded</span>
                                @break
                            @case(0)
                                <span class="badge bg-warning text-dark">In Progress</span>
                                @break
                            @case(1)
                                <span class="badge bg-info text-dark">Submitted to Subject Teacher</span>
                                @break
                            @case(2)
                                <span class="badge bg-primary">Submitted to Exam Office</span>
                                @break
                            @case(3)
                                <span class="badge bg-success">Published</span>
                                @break
                            @default
                                <span class="badge bg-secondary">Unknown Status</span>
                        @endswitch
                    </td>
                </tr>
            @endforeach
        @endforeach
        </tbody>
    </table>
</div>
@endsection