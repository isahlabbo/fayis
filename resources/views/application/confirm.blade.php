@extends('layouts.app')

@section('title')
    {{$student->admission_no}} confirm linking account
@endsection

@section('content')
<div class="row">
    <div class="col-md-3"></div>
    <div class="col-md-6">
        <div class="card-body shadow">
            <p>Pls, confirm the following information of your ward. <b>Note:</b> clicking the button below means that you agree to pay any payment attached to
             this student</p>
            
            <p>
            <form action="{{route('dashboard.guardian.child.link',[$student->id])}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="class">Class</label>
                    <input type="text" name="class" id="class" disabled class="form-control" value="{{$student->activeSectionClass()->name}}">
                </div>
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{$student->name}}">
                </div>
                <div class="form-group">
                    <label for="date_of_birth">Date of Birth</label>
                    <input type="date" name="date_of_birth" id="date_of_birth" class="form-control" value="{{$student->date_of_birth}}">
                </div>
                <div class="form-group">
                    <label for="gender">Student Type</label>
                    <select name="student_type" id="gender" class="form-control">
                    <option value="">Select Student Type</option>
                    @foreach(App\Models\StudentType::all() as $type)
                        <option value="{{$type->id}}">{{$type->name}}</option>
                    @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="gender">Gender</label>
                    <select name="gender" id="gender" class="form-control">
                    <option value="{{$student->gender->id ?? ''}}">{{$student->gender->name ?? 'Select Gender'}}</option>
                    @foreach(App\Models\Gender::all() as $gender)
                        @if($student->gender_id != $gender->id)
                        <option value="{{$gender->id}}">{{$gender->name}}</option>
                        @endif
                    @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="state">State</label>
                    <select name="state" id="state" class="form-control">
                        <option value="">Select State</option>
                        @foreach(App\Models\State::all() as $state)
                        <option value="{{$state->id}}">{{$state->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="state">Local Government</label>
                    <select name="lga" id="state" class="form-control">
                        <option value="">Select LGA</option>
                    </select>
                </div>
                <button class="btn btn-primary">Update & Link</button>
            </form>
            </p>
        </div>
    </div>
    <div class="col-md-3"></div>
</div>
@endsection