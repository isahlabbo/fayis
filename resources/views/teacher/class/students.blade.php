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
                <th>GUARDIAN EMAIL</th>
                <th>ADDRESS</th>
                <th>
                    
                </th>
                
            </tr>
        </thead>
        <tbody>
            @foreach($sectionClass->sectionClassStudents->where('status','Active') as $sectionClassStudent)
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>
                        @if($sectionClassStudent->student->picture)
                            <img src="{{$sectionClassStudent->student->profileImage()}}" alt="" height="120" width="120" class="rounded">
                        @else
                            <img src="{{asset('assets/images/user.jpg')}}" width="120" height="120" class="rounded" alt="">
                        @endif
                    </td>

                    <td>{{$sectionClassStudent->student->name}}</td>
                    <td>{{$sectionClassStudent->student->admission_no}}</td>
                    <td>{{$sectionClassStudent->student->gender->name ?? ''}}</td>
                    <td>{{$sectionClassStudent->student->guardian->name ?? ''}}</td>
                    <td>{{$sectionClassStudent->student->guardian->phone ?? ''}}</td>
                    <td>{{$sectionClassStudent->student->guardian->email ?? ''}}</td>
                    <td>{{$sectionClassStudent->student->guardian->residence_address ?? ''}}</td>
                    <td>
                       
                    </td>
                    
                </tr>
            @endforeach
        </tbody>
        </table>
    @endsection
</x-app-layout>
