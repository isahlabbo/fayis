@extends('layouts.app')

@section('title')
    Assignment - {{$upload->term->name}}
@endsection

@section('breadcrumb')

@endsection

@section('content')
<!-- display the progress bar of the upload using level 0-3 where 1 is first_ca 2 second ca and 3 is exam update-->

<div class="progress" style="height: 40px; font-size:20px;">
    @php
    $level = $upload->level;
    $percentage = ($level / 4) * 100;
    @endphp
    <div class="progress-bar" role="progressbar" style="width: {{$percentage}}%;" aria-valuenow="{{$percentage}}" aria-valuemin="0" aria-valuemax="100">{{$percentage}}%</div>
</div>
<p class="mt-2">Uploaded: {{$level}} / 4</p> 

<!-- display form use table to restructure its content of name and input  to insert the firts ca of the student of each student available in the class -->
<div class="row">
    <div class="col-md-1"></div>
    <div class="col-md-10">
        <div class="alert alert-info text-center">Enter Assignment or Attendance Scores for {{$upload->sectionClassSubjectTeacher->sectionClassSubject->sectionClass->name}} - {{$upload->sectionClassSubjectTeacher->sectionClassSubject->subject->name}} for {{$upload->term->name}}</div>
            <form action="{{route('teacher.subject.assignment.store',[$upload->id])}}" method="post">
                @csrf
                <table class="table table-sm ">
                    <thead>
                        <tr>
                            <th>S/N</th>
                            <th>STUDENT NAME</th>
                            <th>ADMISSION NO</th>
                            <th>FIRST CA (15)</th>
                            <th>SECOND CA (15)</th>
                            <th>ASSIGNMANT/ATTENDANCE</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($upload->sectionClassSubjectTeacher->sectionClassSubject->sectionClass->updateAndGetAllActiveStudentResultForThisTerms($upload->id) as $studentResult)
                        @php 
                        $sectionClassStudent = $studentResult[0]->sectionClassStudentTerm->sectionClassStudent;
                        @endphp
                        <tr>
                            <td>{{$loop->iteration}}</td>
                            <td>{{$sectionClassStudent->student->name}}</td>
                            <td>{{$sectionClassStudent->student->admission_no}}</td>
                            <td>{{$studentResult[0]->first_ca}}</td>
                            <td>{{$studentResult[0]->second_ca}}</td>
                            <td>
                                <input type="number" name="scores[{{$studentResult[0]->id}}]" class="form-control" max="10" value="{{$studentResult[0]->assignment}}">
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="form-group">
                     @if($upload->status == 0)
                    <button class="btn btn-primary">Submit Assignment Scores</button> <a class="btn btn-outline-info" href="{{route('teacher.subject.firstca.index',[$upload->id])}}">Goto First CA</a> <a class="btn btn-outline-warning" href="{{route('teacher.subject.secondca.index',[$upload->id])}}">Goto Second CA</a> <a class="btn btn-outline-danger" href="{{route('teacher.subject.exam.index',[$upload->id])}}">Goto Exam Score</a> 
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
