<x-app-layout>
    @section('title')
        e-payment
    @endsection
    
    
    @section('content')
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="text text-primary center">Search Students</h4>
                        <form action="{{route('dashboard.epayment.search')}}" method="post">
                        @csrf
                        <div class="form-group">
                            <label for="">Section</label>
                            <select name="section" id="" class="form-control">
                                <option value="">Select Section</option>
                                @foreach(App\Models\Section::all() as $section)
                                <option value="{{$section->id}}">{{$section->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="">Class</label>
                            <select name="class" id="" class="form-control">
                                <option value="">Select Class</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <button class="btn btn-secondary">Search Student</button>
                            </div>
                        </div>    
                        
                    </form>
                    </div>
                </div>
            </div>
            <div class="col-md-3"></div>
        </div>
        
    @endsection
</x-app-layout>
