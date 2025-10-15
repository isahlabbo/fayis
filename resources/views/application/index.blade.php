@extends('layouts.app')

@section('title')
    {{$session->name}} applications
@endsection

@section('content')
<table class="table" id="myTable">
    <thead>
        <tr>
            <th>S/N</th>
            <th>PICTURE</th>
            <th>APPLICANT NAME</th>
            <th>GUARDIAN NAME</th>
            <th>DATE OF BIRTH</th>
            <th>PLACE OF BIRTH</th>
            <th>STATE</th>
            <th>LGA</th>
            <th>GSM</th>
            <th>STATUS</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
    @foreach($session->applications as $application)
        <tr>
            <td>{{$loop->iteration}}</td>
            <td><img src="{{$application->applicantImage()}}" alt="" width="100" height="100"></td>
            <td>{{$application->name}}</td>
            <td>{{$application->token->guardian->user->name}}</td>
            <td>{{$application->date_of_birth}}</td>
            <td>{{$application->place_of_birth}}</td>
            <td>{{$application->lga->state->name}}</td>
            <td>{{$application->lga->name}}</td>
            <td>{{$application->token->guardian->gsm}}</td>
            <td>{{$application->status}}</td>
            <td><a href="{{route('dashboard.application.view',[$application->id])}}"><button class="btn btn-info"><i class="fas fa-eye"></i>View</button></a></td>
        </tr>
    @endforeach    
    </tbody>
</table>
@endsection