@extends('layouts.app')
@section('title')
    {{$sectionClass->name}} - Attendance
@endsection
@section('breadcrumb')

@endsection 

@section('content')
<div class="alert alert-success">{{$sectionClass->name}} - Attendance for {{$sectionClass->currentSessionTerm()->term->name}}</div>
<table class="table table-sm table-striped">
    <thead>
        <tr>
            <th>S/N</th>
            <th>STUDENT NAME</th>
            <th>ADMISSION NO</th>
            <th>DAYS SCHOOL OPEN</th>
            <th>DAYS PRESENT</th>
            <th>DAYS ABSENT</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @foreach($sectionClass->sectionClassStudents->where('status', 'Active') as $sectionClassStudent)
        @php 
            $attendance = $sectionClassStudent->currentStudentTerm()->sectionClassStudentTermAccessment;
            if(!$attendance){
                $attendance = $sectionClassStudent->currentStudentTerm()->sectionClassStudentTermAccessment::create([
                    'days_school_open' => 0,
                    'days_present' => 0,
                    'days_absent' => 0,
                    'status' => 'Active'
                ]);
            }
        @endphp
        <tr>
            <td>{{$loop->iteration}}</td>
            <td>{{$sectionClassStudent->student->name}}</td>
            <td>{{$sectionClassStudent->student->admission_no}}</td>
            <td>{{$attendance->days_school_open ?? '0'}}</td>
            <td>{{$attendance->days_present ?? '0'}}</td>
            <td>{{$attendance->days_absent ?? '0'}}</td>
            <td><a href="#" data-toggle="modal" data-target="#edit_{{$attendance->id}}" class="btn btn-sm btn-outline-warning"><i class="fas fa-pen"></i></button></td>
        </tr>
        @include('teacher.class.attendance.edit')
        @endforeach
    </tbody>
</table>
@endsection