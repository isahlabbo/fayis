@extends('layouts.app')
    @section('title')
        system users role  configuration
    @endsection
    
    @section('content')
        <table class="table" id="myTable">
        <thead>
            <tr>
                <th>S/N</th>
                <th>ROLE</th>
                <th>PERMISSIONS</th>
                <th>MODELS</th>
                <th><a href=""><button class="btn btn-primary"><i class="fas fa-plus"></i> Role</button></a></th>
            </tr>
        </thead>
        <tbody>
            @foreach(App\Models\Role::all() as $role)
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{$role->name}}</td>
                    <td></td>
                </tr>
            @endforeach
        </tbody>
        </table>
    @endsection
    

