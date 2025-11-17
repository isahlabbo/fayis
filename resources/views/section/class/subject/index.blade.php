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
                    
                    <a href="{{route('section.class.subject.allocation.edit',[$sectionClassSubject->id])}}"><button class="btn btn-outline-secondary">Edit Teacher Allocation</button></a>
                    
                    </td>
                    <td>
                    <a href="{{route('section.class.subject.delete',[$sectionClass->id,$sectionClassSubject->id])}}" onclick="return confirm('are sure, you want to delete this subject')"><button  class="btn btn-outline-danger">Delete</button></a></td>
                </tr>
                
            @endforeach
        </tbody>
        </table>
    @endsection
    
</x-app-layout>
