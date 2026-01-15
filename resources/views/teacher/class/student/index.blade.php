<x-app-layout>
    @section('title')
        {{$sectionClass->name}} students
    @endsection
    @section('breadcrumb')
      
    @endsection
    
    @section('content')
    <h5 class="text-primary">{{$sectionClass->name}} Students</h5>
    <table class="table table-striped " id="myTable">
        <thead>
            <tr>
                <th>S/N</th>
                <th>PICTURE</th>
                <th>NAME</th>
                <th>ADM NO</th>
                <th>GENDER</th>
                <th>GUARDIAN NAME</th>
                <th>GUARDIAN PHONE</th>
                <th>
                    
                </th>
                
            </tr>
        </thead>
        <tbody>
            @foreach($sectionClass->sectionClassStudents->where('status','Active')->sortBy('student.name') as $sectionClassStudent)
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>
                        @if($sectionClassStudent->student->picture)
                            <img src="{{$sectionClassStudent->student->profileImage()}}" alt="" height="70" width="70" class="rounded">
                        @else
                            <img src="{{asset('assets/images/user.jpg')}}" width="70" height="70" class="rounded" alt="">
                        @endif
                    </td>
                    <td>{{$sectionClassStudent->student->name}}</td>
                    <td>{{$sectionClassStudent->student->admission_no}}</td>
                    <td>{{$sectionClassStudent->student->gender->name ?? ''}}</td>
                    <td>{{$sectionClassStudent->student->guardian->name ?? ''}}</td>
                    <td>{{$sectionClassStudent->student->guardian->phone ?? ''}}</td>
                    <td>
                        @if($sectionClassStudent->student->picture)
                        <button class="btn btn-outline-danger" data-toggle="modal" data-target="#edit_{{$sectionClassStudent->student->id}}"><i class="fas fa-undo"></i>Change Picture</button>
                        @else
                        <button class="btn btn-outline-primary" data-toggle="modal" data-target="#edit_{{$sectionClassStudent->student->id}}"><i class="fas fa-redo"></i> Upload picture</button>
                        @endif
                    </td>

                </tr>
                @include('teacher.class.student.edit')
            @endforeach
        </tbody>
        </table>
    @endsection
</x-app-layout>
