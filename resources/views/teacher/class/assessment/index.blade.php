@extends('layouts.app')
@section('title')
    {{$classTeacher->sectionClass->name}} - Assessment
@endsection
@section('breadcrumb')

@endsection 

@section('content')
<div class="alert alert-success">{{$classTeacher->sectionClass->name}} - Assessment for {{$classTeacher->currentSessionTerm()->term->name}}
    <a class="btn btn-outline-success" href="{{route('teacher.class.attendance.index',[$classTeacher->sectionClass->id])}}">Record Attendance</a>
</div>
<table class="table table-sm table-striped">
    <thead>
        <tr>
            <th>S/N</th>
            <th>STUDENT NAME</th>
            <th>ADMISSION NO</th>
            <th>TEACHERS COMMENT</th>
            <th>HEAD OF SCHOOL COMMENT</th>
            
            <th></th>
        </tr>
    </thead>
    <tbody>
        @foreach($classTeacher->sectionClass->sectionClassStudents->where('status', 'Active') as $sectionClassStudent)
        <tr>
            <td>{{$loop->iteration}}</td>
            <td>{{$sectionClassStudent->student->name}}</td>
            <td>{{$sectionClassStudent->student->admission_no}}</td>
            <td>{{$sectionClassStudent->currentStudentTerm()->sectionClassStudentTermAccessment->teacherComment->name ?? ''}}</td>
            <td>{{$sectionClassStudent->currentStudentTerm()->sectionClassStudentTermAccessment->headTeacherComment->name ?? ''}}</td>
            <td><a href="{{route('teacher.class.assessment.edit',$sectionClassStudent->currentStudentTerm()->id)}}" class="btn btn-sm btn-outline-warning"><i class="fas fa-pen"></i></button></td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection