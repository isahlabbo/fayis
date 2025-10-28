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
            <form action="{{route('teacher.subject.exam.store',[$upload->id])}}" method="post">
                @csrf
                <table class="table table-sm ">
                    <thead>
                        <tr>
                            <th>S/N</th>
                            <th>STUDENT NAME</th>
                            <th>ADMISSION NO</th>
                            <th>FIRST CA</th>
                            <th>SECOND CA</th>
                            <th>EXAM</th>
                            <th>TOTAL</th>
                            <th>GRADE</th>
                            <th>POSITION</th>
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
                                <input type="number" name="scores[{{$studentResult[0]->id}}]" class="form-control" max="60" value="{{$studentResult[0]->exam}}">
                            </td>
                            <td>{{$studentResult[0]->first_ca + $studentResult[0]->second_ca + $studentResult[0]->exam}}</td>
                            <td>{{$studentResult[0]->grade}}</td>
                            <td>{{$studentResult[0]->subjectTeacherTermlyUpload->position($studentResult[0]->total) ?? ''}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="form-group">
                    <a class="btn btn-outline-warning" href="{{route('teacher.subject.firstca.index',[$upload->id])}}">Goto First CA Scores</a> <a class="btn btn-outline-danger" href="{{route('teacher.subject.secondca.index',[$upload->id])}}"> Goto Second CA Score</a> <button class="btn btn-primary">Save Exam Scores</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
