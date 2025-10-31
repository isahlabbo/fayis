<x-app-layout>
    @section('title')
        {{$result->sectionClassSubjectTeacher->sectionClassSubject->name}} of {{$result->sectionClassSubjectTeacher->sectionClassSubject->sectionClass->name}}  Result Detail
    @endsection
    @section('breadcrumb')
       
    @endsection
    @section('content')
    <div class="card shadow">
        <div class="card-body">
            <div class="card-header shadow h5">
            {{$result->sectionClassSubjectTeacher->sectionClassSubject->name}} of {{$result->sectionClassSubjectTeacher->sectionClassSubject->sectionClass->name}}  Result Detail
            </div><br>
            <div class="row">
            <table class="table">
                <thead>
                    <tr>
                        <th>S/N</th>
                        <th>PICTURE</th>
                        <th>NAME</th>
                        <th>ADMISSION NO</th>
                        <th>1ST CA</th>
                        <th>2ND CA</th>
                        <th>ASSIGNMENT</th>
                        <th>EXAM</th>
                        <th>TOTAL</th>
                        <th>GRADE</th>
                        <th>POSITION</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($result->studentResults as $studentResult)
                    @if($studentResult->sectionClassStudentTerm->academicSessionTerm->id == $studentResult->currentSessionTerm()->id)
                    <tr>
                        <td>{{$loop->iteration}}</td>
                        <td>
                            @if($studentResult->sectionClassStudentTerm->sectionClassStudent->student->picture != null)
                                <img src="{{$studentResult->sectionClassStudentTerm->sectionClassStudent->student->profileImage()}}" alt="" height="100" width="100" class="rounded">
                            @else
                                <img src="{{asset('images/user.jpg')}}" width="100" height="100" class="rounded" alt="">
                            @endif
                        </td>
                        <td>{{$studentResult->sectionClassStudentTerm->sectionClassStudent->student->name}}</td>
                        <td>{{$studentResult->sectionClassStudentTerm->sectionClassStudent->student->admission_no}}</td>
                        <td>{{$studentResult->first_ca}}</td>
                        <td>{{$studentResult->second_ca}}</td>
                        <td>{{$studentResult->assignment}}</td>
                        <td>{{$studentResult->exam}}</td>
                        <td>{{$studentResult->total}}</td>
                        <td>{{$studentResult->grade}}</td>
                        <td>{{$studentResult->subjectTeacherTermlyUpload->position($studentResult->total)}}</td>
                        <td>
                            <button data-toggle="modal" data-target="#edit_{{$studentResult->id}}"class="btn btn-success">Edit</button>
                        </td>
                    </tr>
                    @include('teacher.class.result.edit')
                    @endif
                    @endforeach
                </tbody>
            </table>
            </div>
        </div>
    </div>
        
    @endsection
    
</x-app-layout>
