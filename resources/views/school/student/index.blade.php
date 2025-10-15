<x-app-layout>
    @section('title')
        {{config('app.name')}} students
    @endsection
    
    
    @section('content')
        <div class="card">
            <div class="card-body">
                <div class="card-header"><a href="{{route('dashboard.student.create')}}">
                    <button class="btn btn-primary">New Admission</button></a>
                </div>
                <br>
                <form action="{{route('dashboard.student.search')}}" method="post">
                @csrf
                <div class="form-group row">
                    <div class="col-md-1"></div>
                    <div class="col-md-2"><label for="">Section</label></div>
                    <div class="col-md-8">
                        <select name="section" id="" class="form-control">
                            <option value="">Select Section</option>
                            @foreach($sections as $section)
                            <option value="{{$section->id}}">{{$section->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-1"></div>
                    <div class="col-md-2"><label for="">Class</label></div>
                    <div class="col-md-8">
                        <select name="class" id="" class="form-control">
                            <option value="">Select Class</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-group row">
                    <div class="col-md-1"></div>
                    <div class="col-md-2"><label for="">Admission No</label></div>
                    <div class="col-md-8">
                        <input type="text" placeholder="Enter Admission No" class="form-control" name="admission_no">
                    </div>
                </div>
                <div class="form-group row">
                    
                    <div class="col-md-3"></div>
                        
                        <div class="col-md-8">
                            <button class="btn btn-secondary">Search Student</button>
                        </div>
                    </div>    
                </div>    
            </form>
            </div>
        </div>
    @endsection
</x-app-layout>
