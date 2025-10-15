<x-app-layout>
    @section('title')
        {{$sectionClass->name}} subjects
    @endsection
    @section('breadcrumb')
       
    @endsection
    @section('content')
        <table class="table">
        <thead>
            <tr>
                <th>S/N</th>
                <th>SUBJECTS</th>
                <th>TEACHER</th>
                
                <th></th>
                <th><button data-toggle="modal" data-target="#addSubject" class="btn btn-outline-primary">Add Subject</button></th>
                @include('section.class.subject.create')
            </tr>
        </thead>
        <tbody>
            @foreach($sectionClass->sectionClassSubjects as $sectionClassSubject)
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{$sectionClassSubject->subject->name}}</td>
                    <td>{{$sectionClassSubject->activeSectionClassSubjectTeacher()->teacher->user->name ?? 'Not Available'}}</td>
                    <td>
                    @if(count($sectionClassSubject->sectionClassSubjectTeachers) > 0)
                        <a href="{{route('section.class.subject.allocation.reCreate',[$sectionClass->id, $sectionClassSubject->activeSectionClassSubjectTeacher()->id])}}"><button class="btn btn-outline-primary">change the teacher</button></a>
                    @else
                        <a href="{{route('section.class.subject.allocation.create',[$sectionClass->id, $sectionClassSubject->id])}}"><button class="btn btn-outline-secondary">Add Teacher</button></a>
                    @endif
                    </td>
                    <td>
                    <button data-toggle="modal" data-target="#subject_{{$sectionClassSubject->id}}" class="btn btn-outline-secondary">Edit</button>
                    <a href="{{route('section.class.subject.delete',[$sectionClass->id,$sectionClassSubject->id])}}" onclick="return confirm('are sure, you want to delete this subject')"><button  class="btn btn-outline-danger">Delete</button></a></td>
                </tr>
                @include('section.class.subject.edit')
            @endforeach
        </tbody>
        </table>
    @endsection
    
</x-app-layout>
