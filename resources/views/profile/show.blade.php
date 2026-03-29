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
                                <div class="row">
                                    <div class="col-md-6">
                                            <div class="mb-3">
                                            <label for="name" class="form-label">Name</label>
                                            <input type="text" name="name" class="form-control" id="name" value="{{Auth::user()->name}}" >
                                        </div>
                                        <div class="mb-3">
                                            <label for="email" class="form-label">Email</label>
                                            <input type="email" name="email" class="form-control" id="email" value="{{Auth::user()->email}}" >
                                        </div>
                                        <div class="mb-3">
                                            <label for="phone" class="form-label">Phone Number</label>
                                            <input type="phone" name="phone" class="form-control" id="phone" value="{{Auth::user()->teacher->phone}}" >
                                        </div>
                                        <div class="mb-3">
                                            <label for="date_of_birth" class="form-label">Date of Birth</label>
                                            <input type="date_of_birth" name="date_of_birth" class="form-control" id="date_of_birth" value="{{Auth::user()->teacher->date_of_birth}}" >
                                        </div>
                                        <div class="mb-3">
                                            <label for="email" class="form-label">State of Origin</label>
                                            <select name="state" class="form-control" id="state" >
                                                <option value="{{Auth::user()->teacher->lga->state->name}}">{{Auth::user()->teacher->lga->state->name}}</option>
                                                @foreach(App\Models\State::all() as $state)
                                                <option value="{{$state->id}}">{{$state->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="lga" class="form-label">LGA of Origin</label>
                                            <select name="lga" class="form-control" id="lga" >
                                                <option value="{{Auth::user()->teacher->lga->id}}">{{Auth::user()->teacher->lga->name}}</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="address" class="form-label">Address</label>
                                            <textarea rows="2" cols="30" name="address" class="form-control" id="address">{{Auth::user()->teacher->address}}</textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label for="marital_status" class="form-label">Marital Status</label>
                                            <select name="marital_status" class="form-control" id="marital_status" >
                                                <option value="{{Auth::user()->teacher->marital_status}}">{{Auth::user()->teacher->marital_status}}</option>
                                                <option value="Single">Single</option>
                                                <option value="Married">Married</option>
                                                <option value="Divorced">Divorced</option>
                                                <option value="Widowed">Widowed</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="role" class="form-label">Change Password</label>
                                            <input type="password" name="password" class="form-control" id="role" value="" placeholder="Enter new password">
                                        </div>
                                        <div class="mb-3">
                                            <label for="picture" class="form-label">Change Profile Picture</label>
                                            <input type="file" name="picture" class="form-control" id="picture" value="" placeholder="Choose picture to upload">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="date_of_appointment" class="form-label">Date of First Appointment</label>
                                            <input type="date" name="date_of_appointment" class="form-control" id="date_of_appointment" value="{{Auth::user()->teacher->date_of_appointment}}" >
                                        </div>
                                        <div class="mb-3">
                                            <label for="email" class="form-label">First Appointment Grade Level</label>
                                            <input type="text" name="appointment_grade_level" class="form-control" id="appointment_grade_level" value="{{Auth::user()->teacher->appointment_grade_level}}" >
                                        </div>
                                        <div class="mb-3">
                                            <label for="email" class="form-label">Present Grade Level</label>
                                            <input type="text" name="present_grade_level" class="form-control" id="present_grade_level" value="{{Auth::user()->teacher->present_grade_level}}" >
                                        </div>
                                        <div class="mb-3">
                                            <label for="email" class="form-label">TRCN Number</label>
                                            <input type="text" name="trcn" class="form-control" id="trcn" value="{{Auth::user()->teacher->trcn}}" >
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="role" class="form-label">Role</label>
                                            <input type="text" name="role" class="form-control" id="role" value="{{Auth::user()->role}}" readonly>
                                        </div>
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Type</th>
                                                    <th>Qualification</th>
                                                    <th><a href="#" class="btn btn-danger" data-toggle="modal" data-target="#addQualification">Add Qualification</a></th>
                                                </tr>
                                                
                                            </thead>
                                            <tbody>
                                                @foreach(Auth::user()->teacher->qualifications as $qualification)
                                                <tr>
                                                    <td>{{$qualification->name}}</td>
                                                    <td><a href="{{$qualification->viewQualification()}}">View Qualification</a></td>
                                                    <td><a href="{{route('profile.qualification.delete',$qualification->id)}}" class="btn btn-danger" onclick="return confirm('Are you sure you want delete this qualification?')"><i class="fas fa-trash"></i></a></td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                
                                
                                
                                <div class="mb-3">
                                    <label for="role" class="form-label"></label>
                                    <button class="btn btn-primary">Save Changes</button>
                                </div>
                            </form>
                            @include('profile.addQualification')
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