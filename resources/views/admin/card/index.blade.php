@extends('layouts.app')

@section('content')
<h3 class="text text-center">Pending ID Card Requests</h3>
<table class="table table-bordered">
    <tr>
        <th>Name</th>
        <th>Position</th>
        <th>Section</th>
        <th>Status</th>
        <th></th>
    </tr>
    @foreach(App\Models\CardRequest::where('status','pending')->get() as $request)
    <tr>
        <td>{{$request->user->name}}</td>
        <td>{{$request->position}}</td>
        <td>{{$request->section->name}}</td>
        <td>{{$request->status}}</td>
        <td>
            <a href="{{route('admin.card.view', $request->user)}}" class="btn btn-outline-primary btn-sm">View Card</a>
            <form action="{{route('admin.card.markAsPrinted',$request)}}" method="post">
                @csrf
                @method('PUT')
                <button onclick="return confirm('Are you sure you have printed this card?')" class="btn btn-outline-danger btn-sm">Mark as Printed</button>
            </form>
        </td>  
    </tr>
    @endforeach
</table>
@endsection
