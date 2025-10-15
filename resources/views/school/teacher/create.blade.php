<x-app-layout>
    @section('title')
        {{config('app.name')}} register new teacher
    @endsection
    @section('breadcrumb')
       {{Breadcrumbs::render('dashboard.teacher.create')}}
    @endsection
    @section('content')
    <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-body">
                    <div class="card-header text text-bold"><b>Register New Teacher</b></div><br>
                    <form action="{{route('dashboard.teacher.register')}}" method="post">
                        @csrf
                        <div class="form-group row">
                            <div class="col-md-3"><label for="">Name</label></div>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="name" value="{{old('name')}}" placeholder="Enter Teacher's Name">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-3"><label for="">Phone</label></div>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="phone" value="{{old('phone')}}" placeholder="Enter Teacher's Phone number">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-3"><label for="">Email</label></div>
                            <div class="col-md-9">
                                <input type="email" class="form-control" name="email" value="{{old('email')}}" placeholder="Enter Teacher's Email">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-3"><label for="">Date Of Birth</label></div>
                            <div class="col-md-9">
                                <input type="date" class="form-control" name="date_of_birth" value="{{old('date_of_birth')}}" placeholder="Teacher's Date of birth">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-3"><label for="">Address</label></div>
                            <div class="col-md-9">
                                <textarea class="form-control" name="address" placeholder="Enter Teacher's Home address" cols="30" rows="3" class="form-controller">{{old('address')}}</textarea>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-3"><label for="">Password</label></div>
                            <div class="col-md-9">
                                <input type="password" class="form-control" name="password">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-3"><label for="">Confirm Password</label></div>
                            <div class="col-md-9">
                                <input type="password" class="form-control" name="password_confirmation">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-2"></div>
                            <div class="col-md-9">
                                <button class="btn btn-secondary">Register</button>
                            </div>
                        </div>    
                    </form>
                </div>
            </div>
        </div>
    </div>   
        
    @endsection
    
</x-app-layout>
