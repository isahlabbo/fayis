@extends('layouts.app')

@section('content')
<h3 class="text text-center">List of {{$type}}</h3>
<table class="table table-bordered">
    <tr>
        <th>Name</th>
        <th>Email</th>
        <th>Role</th>
        <th>Status</th>
        <th></th>
    </tr>
    @foreach(App\Models\User::all() as $user)
    <tr>
        <td>{{$user->name}}</td>
        <td>{{$user->email}}</td>
        <td>{{$user->role}}</td>
        <td>{{$user->status}}</td>
        <td>
            <a href="#" data-toggle="modal" data-target="#edit_{{$user->id}}" class="btn btn-primary btn-sm">View</a>
            <!-- login as user -->
            <a href="{{ route('admin.login-as', $user->id) }}" class="btn btn-success btn-sm">Login as User</a>
        </td>
    </tr>
    @include('admin.user.edit',['user'=>$user])
    @endforeach
</table>
@endsection
