@extends('layouts.app')

@section('title')
    Second CA - {{$upload->term->name}}
@endsection

@section('breadcrumb')

@endsection

@section('content')
<!-- display form use table to restructure its content of name and input  to insert the firts ca of the student of each student available in the class -->
<div class="row">
    <div class="col-md-2"></div>
    <div class="col-md-8">
        <div class="alert alert-info text-center">Enter Second CA Scores for {{$upload->sectionClassSubjectTeacher->sectionClassSubject->sectionClass->name}} - {{$upload->sectionClassSubjectTeacher->sectionClassSubject->subject->name}} for {{$upload->term->name}}</div>
            <form action="{{route('teacher.subject.secondca.store',[$upload->id])}}" method="post">
                @csrf
                <table class="table table-sm ">
                    <thead>
                        <tr>
                            <th>S/N</th>
                            <th>STUDENT NAME</th>
                            <th>ADMISSION NO</th>
                            <th>FIRST CA</th>
                            <th>SECOND CA</th>
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
                            <td>
                                <input type="number" name="scores[{{$studentResult[0]->id}}]" class="form-control" max="20" value="{{$studentResult[0]->second_ca}}">
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="form-group">
                    <button class="btn btn-outline-primary">Save Second CA Scores</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
