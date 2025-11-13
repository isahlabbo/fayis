<x-app-layout>
    @section('title')
        {{config('app.name')}} edit student student
    @endsection
    
    @section('breadcrumb')

    @endsection
    @section('content')
    <div class="row">
        <div class="col-md-1"></div>
        <div class="col-md-10">
            <div class="card shadow">
                <div class="card-body">
                    <div class="card-header text text-bold"><b>EDIT {{$student->name}} INFOR</b></div><br>
                    <form action="{{route('admission.student.update',[$student->id])}}" method="post" enctype="multipart/form-data">
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
                                                <input type="text" class="form-control" name="guardian_name" value="{{$student->guardian->name}}" placeholder="Enter Guardian's Name">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-md-3"><label for="">Email</label></div>
                                            <div class="col-md-9">
                                                <input type="email" class="form-control" name="email" value="{{$student->guardian->email}}" placeholder="Enter Guardian's Email">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-md-3"><label for="">Phone</label></div>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" name="phone" value="{{$student->guardian->phone}}" placeholder="Enter Guardian's Phone Number">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-md-3"><label for="">Address</label></div>
                                            <div class="col-md-9">
                                                <textarea cols="30" class="form-control" rows="2" name="address" placeholder="PLS Enter father address">{{$student->guardian->address}}</textarea cols="" rows="2">
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
                                                <input type="text" class="form-control" name="name" value="{{$student->name}}" placeholder="Enter Teacher's Name">
                                            </div>
                                        </div>
                                        
                                        <div class="form-group row">
                                            <div class="col-md-3"><label for="">Class</label></div>
                                            <div class="col-md-9">
                                                <select name="class" id="" class="form-control">
                                                    <option value="{{$student->activeSectionClass()->id ?? ''}}">{{$student->activeSectionClass()->name ?? 'Select Class'}}</option>
                                                    @foreach(App\Models\SectionClass::all() as $sectionClass)
                                                        <option value="{{$sectionClass->id}}">{{$sectionClass->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-md-3"><label for="">Admission No</label></div>
                                            <div class="col-md-9">
                                                <input type="text" name="admission_no" id="" class="form-control" value="{{$student->admission_no}}">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-md-3"><label for="">Gender</label></div>
                                            <div class="col-md-9">
                                                <select name="gender" id="" class="form-control">
                                                    <option value="{{$student->gender->id}}">{{$student->gender->name}}</option>
                                                    @foreach(App\Models\Gender::all() as $gender)
                                                        @if($student->gender->id != $gender->id)
                                                            <option value="{{$gender->id}}">{{$gender->name}}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-md-3"><label for="">Date Of Birth</label></div>
                                            <div class="col-md-9">
                                                <input type="date" class="form-control" name="date_of_birth" value="{{$student->date_of_birth}}" placeholder="Teacher's Date of birth">
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
                                                <button class="btn btn-block btn-primary">Update</button>
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
