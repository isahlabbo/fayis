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
                    <a href="{{route('section.class.download',[$sectionClass->id])}}">
                    <button class="btn btn-outline-primary"><i class="fas fa-download"></i> Download</button></a>
                    <button class="btn btn-outline-secondary" data-toggle="modal" data-target="#upload"> <i class="fas fa-upload"></i> upload</button></a>
                </th>
                
            </tr>
            @include('section.class.student.upload')
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
                    <td>{{$sectionClassStudent->student->guardian->gsm ?? ''}}</td>
                    <td>{{$sectionClassStudent->student->guardian->email ?? ''}}</td>
                    <td>{{$sectionClassStudent->student->guardian->residence_address ?? ''}}</td>
                    <td>
                        <a href="{{route('section.class.student.edit',[$sectionClassStudent->student->id])}}">
                            <button class="btn btn-secondary"><i class="fas fa-edit"></i>Edit</button>
                        </a>
                        <a href="{{route('section.class.student.delete',[$sectionClassStudent->student->id])}}" onclick="return confirm('Are you sure, you want delete this student record')">
                        <button class="btn btn-danger"><i class="fas fa-trash"></i>Delete</button>
                        </a>
                    </td>
                    
                </tr>
            @endforeach
        </tbody>
        </table>
    @endsection
</x-app-layout>
