<x-app-layout>
    @section('title')
        {{config('app.name')}} register new student
    @endsection
    @section('breadcrumb')
       {{Breadcrumbs::render('dashboard.student.create')}}
    @endsection
    @section('content')
    <div class="row">
        <div class="col-md-1"></div>
        <div class="col-md-10">
            <div class="card shadow">
                <div class="card-body">
                    <div class="card-header text text-bold"><b>Register New Student</b></div><br>
                    <form action="{{route('dashboard.student.register')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card shadow">
                                    <div class="card-body">
                                        <div class="card-header">
                                            <b>Guardian Information</b>
                                        </div><br>
                                        <div class="form-group row">
                                            <div class="col-md-3"><label for="">Name</label></div>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" name="guardian_name" value="{{old('guardian_name')}}" placeholder="Enter Guardian's Name">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-md-3"><label for="">Email</label></div>
                                            <div class="col-md-9">
                                                <input type="email" class="form-control" name="email" value="{{old('email')}}" placeholder="Enter Guardian's Email">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-md-3"><label for="">Phone</label></div>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" name="phone" value="{{old('phone')}}" placeholder="Enter Guardian's Phone Number">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-md-3"><label for="">Address</label></div>
                                            <div class="col-md-9">
                                                <textarea cols="30" class="form-control" rows="2" name="address" placeholder="PLS Enter father address">{{old('address')}}</textarea cols="" rows="2">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card shadow">
                                    <div class="card-body">
                                        <div class="card-header">
                                            <b>Student Information</b>
                                        </div><br>
                                        <div class="form-group row">
                                            <div class="col-md-3"><label for="">Name</label></div>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" name="name" value="{{old('name')}}" placeholder="Enter Teacher's Name">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-md-3"><label for="">Section</label></div>
                                            <div class="col-md-9">
                                            <select name="section" id="" class="form-control">
                                                <option>Select Section</option>
                                                @foreach($sections as $section)
                                                    <option value="{{$section->id}}">{{$section->name}} Section</option>
                                                @endforeach
                                            </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-md-3"><label for="">Class</label></div>
                                            <div class="col-md-9">
                                                <select name="class" id="" class="form-control">
                                                    <option>Select Class</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-md-3"><label for="">Gender</label></div>
                                            <div class="col-md-9">
                                                <select name="gender" id="" class="form-control">
                                                    <option>Select Gender</option>
                                                    <option value="1">Male</option>
                                                    <option value="2">Female</option>

                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-md-3"><label for="">Date Of Birth</label></div>
                                            <div class="col-md-9">
                                                <input type="date" class="form-control" name="date_of_birth" value="{{old('date_of_birth')}}" placeholder="Teacher's Date of birth">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-md-3"><label for="">Select Picture</label></div>
                                            <div class="col-md-9">
                                                <input type="file" class="form-control" name="picture" value="{{old('picture')}}" placeholder="Teacher's Date of birth">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-md-2"></div>
                                            <div class="col-md-9">
                                                <button class="btn btn-block btn-primary">Register</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>    
                    </form>
                </div>
            </div>
        </div>
    </div>   
        
    @endsection
    
</x-app-layout>
