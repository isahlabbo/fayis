@extends('layouts.app')

@section('title', 'none teaching staff')


@section('content')
<table class="table" id="myTable">
    <thead>
        <tr>
            <th>S/N</th>
            <th>NAME</th>
            <th>EMAIL</th>
            <th>PASSWORD </th>
            <th><button class="btn btn-outline-primary" data-toggle="modal" data-target="#newUser"><i class="fas fa-plus"></i> User</button></th>
        </tr>
    </thead>
    <tbody>
    @include('administration.staff.create')
    @foreach(App\Models\User::where('role', 'staff')->get() as $user)
        <tr>
            <td>{{$loop->iteration}}</td>
            <td>{{$user->name}}</td>
            <td>{{$user->email}}</td>
            <td>*******</td>
            <td>
              <a href="#" data-toggle="modal" data-target="#edit_{{$user->id}}"><button class="btn btn-outline-info"><i class="fas fa-edit"></i></button></a>
              <a href="{{route('administration.staff.delete',[$user->id])}}" onclick="return confirm('Are you sure, you want to delete this staff record?');" class="btn btn-outline-danger"><i class="fas fa-trash"></i></a>
            </td>
        </tr>
        @include('administration.staff.edit')
    @endforeach    
    </tbody>
</table>

@endsection