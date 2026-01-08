<x-app-layout>
    @section('title')
        {{$academicSession->name}} academic session configuration
    @endsection
    @section('breadcrumb')
       
    @endsection
    @section('content')
    <div class="row">
    <div class="col-md-1"></div>
    <div class="col-md-10">
    <div class="card shadow">
        <div class="card-body">
        <div class="card-header text text-bold"><b>{{config('app.name')}} <i>{{$academicSession->name}}</i> Academic Session Configuration</b></div><br>
            <form action="{{route('administration.session.configuration.term.update')}}" method="post">
                @csrf
                <div class="row">
                <div class="col-md-6">
                    <div class="form-group row">
                        <div class="col-md-5"><label for="">Session</label></div>
                        <input type="hidden" value="{{$academicSession->id}}" name="academicSessionId">
                        <div class="col-md-7">
                            <input type="text" name="name" value="{{$academicSession->name}}" disabled class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-5"><label for="">Session Start At</label></div>
                        <div class="col-md-7">
                            <input type="date" name="session_start_at" value="{{$academicSession->start_at}}" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-5"><label for="">Session End At</label></div>
                        <div class="col-md-7">
                            <input type="date" name="session_end_at" value="{{$academicSession->end_at}}" class="form-control">
                        </div>
                    </div>
                    <hr>
                </div>
                @foreach($academicSession->academicSessionTerms as $academicSessionTerm)
                <div class="col-md-6">
                    <div class="form-group row">
                        <div class="col-md-5"><label for="">{{$academicSessionTerm->term->name}}</label></div>
                        <div class="col-md-7">
                            <input type="text" name="name" value="{{$academicSessionTerm->term->name}}" disabled class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-5"><label for="">{{$academicSessionTerm->term->name}} Start At</label></div>
                        <div class="col-md-7">
                            <input type="date" name="{{$academicSessionTerm->term->name}}_start_at" value="{{$academicSessionTerm->start_at}}" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-5"><label for="">{{$academicSessionTerm->term->name}} End At</label></div>
                        <div class="col-md-7">
                            <input type="date" name="{{$academicSessionTerm->term->name}}_end_at" value="{{$academicSessionTerm->end_at}}" class="form-control">
                        </div>
                    </div>
                    <hr>
                </div>    
                @endforeach
                </div>
                <div class="form-group row">
                    <div class="col-md-8"></div>
                        <div class="col-md-4">
                            <button class="btn btn-block btn-secondary">Update</button>
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
