@extends('layouts.app')

@section('title', 'id cards')


@section('content')
<table class="table" id="myTable">
    <thead>
        <tr>
            <th>S/N</th>
            <th>NAME</th>
            <th>EMAIL</th>
            <th>SECTION</th>
            <th>POSITION</th>
            <th>STAFF ID</th>
            <th>REASON</th>
            <th>STATUS</th>
            <th><button class="btn btn-outline-primary" data-toggle="modal" data-target="#newUser"><i class="fas fa-plus"></i> Request</button></th>
        </tr>
    </thead>
    <tbody>
    @include('administration.card.create')
    @foreach(App\Models\CardRequest::all() as $request)
        <tr>
            <td>{{$loop->iteration}}</td>
            <td>{{$request->user->name}}</td>
            <td>{{$request->user->email}}</td>
            <td>{{$request->section->name}}</td>
            <td>{{$request->position}}</td>
            <td>{{$request->staffID}}</td>
            <td>{{$request->reason}}</td>
            <td>{{$request->status}}</td>
            <td>
                <a href="{{route('administration.card.approve',[$request->id])}}" onclick="return confirm('Are you sure, you want to approve this ID Card Request?');" class="btn btn-outline-success"><i class="fas fa-check"></i></a>
                <a href="#" data-toggle="modal" data-target="#edit_{{$request->id}}"><button class="btn btn-outline-info"><i class="fas fa-edit"></i></button></a>
                <a href="{{route('administration.card.delete',[$request->id])}}" onclick="return confirm('Are you sure, you want to delete this ID Card Request?');" class="btn btn-outline-danger"><i class="fas fa-trash"></i></a>
            </td>
        </tr>
        @include('administration.card.edit')
    @endforeach    
    </tbody>
</table>

@endsection