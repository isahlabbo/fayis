@extends('layouts.app')
@section('title','Profile')
@section('breadcrumb')

@endsection
@section('content')
    
            
            
            <div class="row">
                <div class="col-md-1"></div>
                <div class="col-md-10">
                    <div class="card shadow p-4">
                        <div class="row">
                            <div class="col-md-10"></div>
                            <div class="col-md-2">  
                            @if(Auth::user()->profile_photo_path)
                                <img src="{{Auth::user()->profileImage()}}" alt="" height="120" width="120" class="rounded">
                            @else
                                <img src="{{asset('images/user.jpg')}}" width="120" height="120" class="rounded" alt="">
                            @endif
                            </div>
                        </div>
                            
                            
                        <div class="card-body">
                            <!-- display user information in editable form -->
                            <form action="{{route('profile.update', Auth::user()->id)}}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="mb-3">
                                    <label for="name" class="form-label">Name</label>
                                    <input type="text" name="name" class="form-control" id="name" value="{{Auth::user()->name}}" >
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" name="email" class="form-control" id="email" value="{{Auth::user()->email}}" >
                                </div>
                                <div class="mb-3">
                                    <label for="role" class="form-label">Role</label>
                                    <input type="text" name="role" class="form-control" id="role" value="{{Auth::user()->role}}" readonly>
                                </div>
                                <div class="mb-3">
                                    <label for="role" class="form-label">Change Password</label>
                                    <input type="password" name="password" class="form-control" id="role" value="" placeholder="Enter new password">
                                </div>
                                <div class="mb-3">
                                    <label for="picture" class="form-label">Upload Your Picture</label>
                                    <input type="file" name="picture" class="form-control" id="picture" value="" placeholder="Choose picture to upload">
                                </div>
                                <div class="mb-3">
                                    <label for="role" class="form-label"></label>
                                    <button class="btn btn-primary">Save Changes</button>
                                </div>
                            </form>
                            <hr>
                            <!-- button to apply for id card -->
                             @if(Auth::user()->cardRequests->where('status','Pending')->count() > 0)
                                <div class="alert alert-info">You have a pending ID card request. <a href="{{route('profile.card',[Auth::user()->id])}}">click here to view the card before printing</a></div>
                            @else
                            <button class="btn btn-primary" data-toggle="modal" data-target="#applyCard"><i class="fas fa-id-card"></i> Apply for ID Card</button>
                            @include('profile.applyCardModal')
                        @endif
                        </div>
                    </div>
                </div>
            </div>
       
@endsection