<x-app-layout>
    @section('title')
        {{config('app.name')}} Teachers
    @endsection
    @section('breadcrumb')
    
    @endsection
    @section('content')
        <h5>{{$teacher->user->name}}</h5>
        <table class="table">
        <thead>
            <tr>
                <th>S/N</th>
                <th>Subject</th>
                <th>Class</th>
                <td>Upload</td>
                <th>
                <button data-toggle="modal" data-target="#newSubject" class="btn btn-primary">Add Subject</button></th>
                @include('school.teacher.subjects.create')
            </tr>
        </thead>
        <tbody>
            @foreach($teacher->sectionClassSubjectTeachers as $subjectTeacher)
            @if($subjectTeacher->sectionClassSubject)
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{$subjectTeacher->sectionClassSubject->subject->name ?? ''}}</td>
                    <td>{{$subjectTeacher->sectionClassSubject->sectionClass->name ?? ''}}</td>
                    <td>{{count($subjectTeacher->subjectTeacherTermlyUploads)}}</td>
                    <td>
                        <a href="{{route('administration.teacher.subject.remove',[$teacher->id, $subjectTeacher->id])}}" class="btn btn-danger"
                        onclick="return confirm('Are sure you want do this? note doing it might delete any result uploaded by the teacher')">Remove</a>
                    </td>
                </tr>
                @endif
            @endforeach
        </tbody>
        </table>
    @endsection
    
</x-app-layout>
