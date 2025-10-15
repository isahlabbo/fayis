@extends('layouts.app')
    @section('title')
        system use role and permission configuration
    @endsection
    
    @section('content')
        <table class="table" id="myTable">
        <thead>
            <tr>
                <th>S/N</th>
                <th>PERMISSION</th>
                <th>ROLES</th>
                <th>MODELS</th>
                <th><a href=""><button class="btn btn-primary"><i class="fas fa-plus"></i> Permission</button></a></th>
            </tr>
        </thead>
        <tbody>
            @foreach(App\Models\Permission::all() as $permission)
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{$permission->name}}</td>
                    <td></td>
                </tr>
            @endforeach
        </tbody>
        </table>
    @endsection
    

