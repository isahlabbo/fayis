<x-app-layout>
    @section('title')
        {{$sectionClass->name}}  subjects results
    @endsection
    @section('breadcrumb')
       {{Breadcrumbs::render('dashboard.section.class.result',$sectionClass)}}
    @endsection
    @section('content')
    <div class="card">
    <div class="card-body">
        <div class="card-header h4">{{$sectionClass->name}} Subject Results Information 
        <a href="{{route('dashboard.section.class.result.accessment.download',[$sectionClass->id])}}"><button class="btn btn-secondary">Download Accessment</button></a>
        <button class="btn btn-primary" data-toggle="modal" data-target="#class_{{$sectionClass->id}}">Upload Accessment</button>
        <a href="{{route('dashboard.section.class.result.accessment.view',[$sectionClass->id])}}"><button class="btn btn-secondary">View Accessment</button></a>
        </div>
    </div>
    
    </div>
        <table class="table">
        <thead>
            <tr>
                <th>S/N</th>
                <th>SUBJECTS</th>
                <th></th>
                <th>DOWNLOADS</th>
                <th></th>
                <th>UPLOADS</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sectionClass->sectionClassSubjects as $sectionClassSubject)
                @include('section.class.result.access')
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
    @endsection
    
</x-app-layout>
