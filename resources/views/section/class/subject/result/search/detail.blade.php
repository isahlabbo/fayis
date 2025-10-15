<x-app-layout>
    @section('title')
        {{$subjectTeacherTermlyUpload->sectionClassSubjectTeacher->sectionClassSubject->name}} of {{$subjectTeacherTermlyUpload->sectionClassSubjectTeacher->sectionClassSubject->sectionClass->name}}  Result Detail
    @endsection
    @section('breadcrumb')
       {{Breadcrumbs::render('dashboard.result.summary.detail',
       $subjectTeacherTermlyUpload->academicSessionTerm->academicSession, 
       $subjectTeacherTermlyUpload->term,$subjectTeacherTermlyUpload->sectionClassSubjectTeacher->sectionClassSubject)}}
    @endsection
    @section('content')
    <div class="card shadow">
        <div class="card-body">
            <div class="card-header shadow h3">
            <b>{{$subjectTeacherTermlyUpload->sectionClassSubjectTeacher->sectionClassSubject->name}} of {{$subjectTeacherTermlyUpload->sectionClassSubjectTeacher->sectionClassSubject->sectionClass->name}}  Result Detail</b>
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
                        <th>EXAM</th>
                        <th>TOTAL</th>
                        <th>GRADE</th>
                        <th>POSITION</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($subjectTeacherTermlyUpload->studentResults as $studentResult)
                    <tr>
                        <td>{{$loop->iteration}}</td>
                        <td>
                            @if($studentResult->sectionClassStudentTerm->sectionClassStudent->student->picture)
                                <img src="{{$studentResult->sectionClassStudentTerm->sectionClassStudent->student->profileImage()}}" alt="" height="100" width="100" class="rounded">
                            @else
                                <img src="{{asset('assets/images/user.jpg')}}" width="100" height="100" class="rounded" alt="">
                            @endif
                        </td>
                        <td>{{$studentResult->sectionClassStudentTerm->sectionClassStudent->student->name}}</td>
                        <td>{{$studentResult->sectionClassStudentTerm->sectionClassStudent->student->admission_no}}</td>
                        <td>{{$studentResult->first_ca}}</td>
                        <td>{{$studentResult->second_ca}}</td>
                        <td>{{$studentResult->exam}}</td>
                        <td>{{$studentResult->total}}</td>
                        <td>{{$studentResult->grade}}</td>
                        <td>{{$studentResult->subjectTeacherTermlyUpload->position($studentResult->total)}}</td>
                        <td>
                            <a href="{{route('dashboard.section.class.subject.result.summary.detail.edit',[$studentResult->id])}}">
                                <button class="btn btn-success">Edit</button>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
        </div>
    </div>
        
    @endsection
    
</x-app-layout>
