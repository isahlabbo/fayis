<x-app-layout>
    @section('title')
        {{config('app.name')}} {{$sectionClass->name}} students accessment
    @endsection
    @section('breadcrumb')
       {{Breadcrumbs::render('dashboard')}}
    @endsection
    @php
       $comments = App\Models\TeacherComment::all();
    @endphp
    @section('content')
        <table class="table table-striped " id="datatable-buttons">
        <thead>
            <tr>
                <th>S/N</th>
                <th>NAME</th>
                <th>ADMISSION NO</th>
                <th>GENDER</th>
                <th>ACCESSMENT STATUS</th>
                <th></th>
                
            </tr>
        </thead>
        <tbody>
            @foreach($sectionClass->sectionClassStudents->where('status','Active') as $sectionClassStudent)
                @include('school.student.accessment.access')
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{$sectionClassStudent->student->name}}</td>
                    <td>{{$sectionClassStudent->student->admission_no}}</td>
                    <td>{{$sectionClassStudent->student->gender()}}</td>
                    <td>{{$sectionClassStudent->currentStudentTerm()->sectionClassStudentTermAccessment ? 'Accessed' :'Pending'}}</td>
                    <td>
                        @if($sectionClassStudent->currentStudentTerm()->sectionClassStudentTermAccessment)

                        @else
                        <a href="{{route('dashboard.student.accessment.create',[$sectionClassStudent->id])}}"><button class="btn btn-secondary" >MAKE ACCESSMENT</button></a>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
        </table>
    @endsection
    
</x-app-layout>
