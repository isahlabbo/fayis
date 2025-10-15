@extends('layouts.app')

@section('title')
    registered users
@endsection

@section('content')
<table class="table" id="myTable">
    <thead>
        <tr>
            <th>S/N</th>
            <th>NAME</th>
            <th>EMAIL</th>
            <th>PASSWORD </th>
            <th>ROLE</th>
            <th><button class="btn btn-primary" data-toggle="modal" data-target="#newUser"><i class="fas fa-plus"></i> User</button></th>
        </tr>
    </thead>
    <tbody>
    @include('administration.user.create')
    @foreach(App\Models\User::where('role', '!=', 'guardian')->get() as $user)
        <tr>
            <td>{{$loop->iteration}}</td>
            <td>{{$user->name}}</td>
            <td>{{$user->email}}</td>
            <td>*******</td>
            <td>{{$user->role}}</td>
            <td><a href="#" data-toggle="modal" data-target="#edit_{{$user->id}}"><button class="btn btn-info"><i class="fas fa-edit"></i>Edit</button></a></td>
        </tr>
        @include('administration.user.edit')
    @endforeach    
    </tbody>
</table>
@endsection