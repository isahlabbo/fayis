<x-app-layout>
    @section('title')
        {{config('app.name')}} Teachers
    @endsection
    @section('breadcrumb')
       {{Breadcrumbs::render('dashboard.teacher')}}
    @endsection
    @section('content')
        <table class="table">
        <thead>
            <tr>
                <th>S/N</th>
                <th>NAME</th>
                <th>EMAIL</th>
                <th>PHONE</th>
                <th>ADDRESS</th>
                <th>SUBJECTS</th>
                <th></th>
                <th><a href="{{route('dashboard.teacher.create')}}">
                <button class="btn btn-primary">New Teacher</button></a></th>
            </tr>
        </thead>
        <tbody>
            @foreach($teachers as $teacher)
            @include('school.teacher.edit')
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{$teacher->user->name}}</td>
                    <td>{{$teacher->user->email}}</td>
                    <td>{{$teacher->phone}}</td>
                    <td>
                        {{$teacher->address}}
                    </td>
                    <td>
                    <a href="{{route('dashboard.section.class.subject.allocation.index',[$teacher->id])}}">
                    <button class="btn btn-primary">{{count($teacher->sectionClassSubjectTeachers)}}</button></a>
                    </td>
                    <td>
                        
                    </td>
                    <td><button data-toggle="modal" data-target="#teacher_{{$teacher->id}}" class="btn btn-secondary">Edit</button>
                    <a href="{{route('dashboard.teacher.delete',[$teacher->id])}}" onclick="return confirm('Are you sure you want delete this teacher from teachers records')"><button class="btn btn-danger">Delete</button></a></td>
                </tr>
            @endforeach
        </tbody>
        </table>
    @endsection
    
</x-app-layout>
