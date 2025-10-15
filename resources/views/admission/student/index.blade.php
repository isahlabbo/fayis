<x-app-layout>
    @section('title')
        search students
    @endsection
    @section('breadcrumb')
      
    @endsection
    
    @section('content')
       <div class="row">
    <div class="col-md-2"></div>
    <div class="col-md-8">
    <div class="card shadow">
        <div class="card-body">
        <div class="card-header text text-bold"><b>Search Student</b></div><br>
        <form action="{{route('admission.student.search')}}" method="post">
                @csrf
                <div class="form-group row">
                    <div class="col-md-1"></div>
                    <div class="col-md-2"><label for="">Section</label></div>
                    <div class="col-md-8">
                        <select name="section" id="" class="form-control">
                            <option value="">Select Section</option>
                            @foreach(App\Models\Section::all() as $section)
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
                    
                    <div class="col-md-3"></div>
                        
                        <div class="col-md-8">
                            <button class="btn btn-secondary">View Student List</button>
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
