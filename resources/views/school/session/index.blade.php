<x-app-layout>
    @section('title')
        {{config('app.name')}} academic session
    @endsection
    @section('breadcrumb')
       
    @endsection
    @section('content')
        <table class="table">
        <thead>
            <tr>
                <th>S/N</th>
                <th>ACADEMIC SESSION</th>
                <th>SESSION START</th>
                <th>SESSION END</th>
                <th>APPLICATION START</th>
                <th>APPLICATION END</th>
                <th>INTERVIEW START</th>
                <th>INTERVIEW END</th>
                <th>APPLICATION FORM FEE</th>
                <th>STATUS</th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                
            </tr>
        </thead>
        <tbody>
            @foreach($academicSessions as $academicSession)
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{$academicSession->name}}</td>
                    <td>{{$academicSession->start_at}}</td>
                    <td>{{$academicSession->end_at}}</td>
                    <td>{{$academicSession->application_start}}</td>
                    <td>{{$academicSession->application_end}}</td>
                    <td>{{$academicSession->interview_start}}</td>
                    <td>{{$academicSession->interview_end}}</td>
                    <td>{{$academicSession->form_fee}}</td>
                    <td>{{$academicSession->status}}</td>
                    <td>
                    <a href="#" data-toggle="modal" data-target="#edit_{{$academicSession->id}}"><button class="btn btn-warning"><i class="fas fa-edit"></i>Edit</button></a>
                    </td>
                    <td>
                    @if($academicSession->status != 'Active')
                        <a href="{{route('dashboard.session.activate',[$academicSession->id])}}"><button class="btn btn-success"> <i class="fas fa-check"></i> Mark as Current Session</button></a>
                    @endif
                    </td>   
                    <td>   
                        <a href="{{route('dashboard.session.configure',[$academicSession->id])}}"><button class="btn btn-primary"><i class="fas fa-cog"></i>Configure Termely Calendar</button></a>
                    </td>
                    <td>   
                        <a href="{{route('dashboard.session.configure',[$academicSession->id])}}"><button class="btn btn-danger"><i class="fas fa-trash"></i> Delete</button></a>
                    </td>
                </tr>
                @include('school.session.edit')
            @endforeach
        </tbody>
        </table>
        {{$academicSessions->links()}}
    @endsection
    
</x-app-layout>
