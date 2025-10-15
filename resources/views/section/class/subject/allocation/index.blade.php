<x-app-layout>
    @section('title')
        {{$teacher->user->name}} allocated subjects
    @endsection
    @section('breadcrumb')

    @endsection
    @section('content')
        <table class="table">
        <thead>
            <tr>
                <th>S/N</th>
                <th>SUBJECTS</th>
                <th>CLASS</th>
                <th></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach($teacher->sectionClassSubjectTeachers as $sectionClassSubjectTeacher)
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{$sectionClassSubjectTeacher->sectionClassSubject->subject->name}}</td>
                    <td>{{$sectionClassSubjectTeacher->sectionClassSubject->sectionClass->name}}</td>
                    <td>
                        <a href="{{route('teacher.download.scoresheet',[$sectionClassSubjectTeacher->id])}}">
                        <button class="btn btn-primary">Download Score Sheet</button>
                        </a>
                    </td>
                    <td>
                        <a href="{{route('teacher.upload.scoresheet',[$sectionClassSubjectTeacher->sectionClassSubject->id])}}"><button class="btn btn-secondary">Upload Result</button></a>
                    </td>
                </tr>
            @endforeach
        </tbody>
        </table>
    @endsection
    
</x-app-layout>
