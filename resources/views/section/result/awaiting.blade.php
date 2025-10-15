<x-app-layout>
    @section('title')
        sections
    @endsection
    @section('breadcrumb')
       {{Breadcrumbs::render('dashboard')}}
    @endsection
    @section('content')
        <div class="card shadow">
            <div class="card-body">
                <div class="card-header text text-center h4 shadow">{{strtoupper(config('app.name'))}} {{$sectionClass->name}} AWAITING RESULTS</div>
                <table class="table">
                    <thead>
                        <tr>
                            <th>S/N</th>
                            <th>SUBJECT</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sectionClass->subjectResultUploads()['awaiting'] as $sectionClassSubject)
                            @include('school.teacher.scoreSheet.upload')
                            <tr>
                                <td>{{$loop->iteration}}</td>
                                <td>{{$sectionClassSubject->subject->name}}</td>
                                <td>
                                    <a href="{{route('dashboard.teacher.download.scoresheet',[$sectionClassSubject->activeSectionClassSubjectTeacher()->id])}}">
                                    <button class="btn btn-primary">Download Score Sheet</button>
                                    </a>
                                </td>
                                <td><button class="btn btn-success">{{count($sectionClassSubject->sectionClassSubjectDownloads->where('academic_session_term_id',$sectionClassSubject->currentSessionTerm()->id))}}</button></td>
                                <td>
                                    <btton data-toggle="modal" data-target="#upload_{{$sectionClassSubject->id}}" class="btn btn-secondary">Upload Result</button>
                                </td>
                                <td><button class="btn btn-success">{{count($sectionClassSubject->sectionClassSubjectUploads->where('academic_session_term_id',$sectionClassSubject->currentSessionTerm()->id))}}</button></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endsection
    
</x-app-layout>
