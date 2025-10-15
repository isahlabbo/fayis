<x-app-layout>
    @section('title')
        {{$sectionClassSubject->subject->name}} subject teacher allocation
    @endsection
    @section('breadcrumb')
       {{Breadcrumbs::render('dashboard.section.class.subject.allocation.create',$sectionClassSubject)}}
    @endsection
    @section('content')
    <div class="row">
    <div class="col-md-2"></div>
    <div class="col-md-8">
    <div class="card shadow">
        <div class="card-body">
        <div class="card-header text text-bold"><b>Assign Techer To {{$sectionClassSubject->subject->name}} of {{$sectionClassSubject->sectionClass->name}}</b></div><br>
        <form action="{{route('dashboard.section.class.subject.allocation.register',[$sectionClassSubject->id])}}" method="post">
                @csrf
                <div class="form-group row">
                    <div class="col-md-4"><label for="">Teacher</label></div>
                       <input type="hidden" value="{{$sectionClassSubject->id}}" name="sectionClassSubjectId">
                        <div class="col-md-8">
                            <select name="teacher" id="" class="form-control">
                                <option value="">Select Teacher</option>
                                @foreach($teachers as $teacher)
                                <option value="{{$teacher->id}}">{{$teacher->user->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>    
                </div>
                <div class="form-group row">
                    <div class="col-md-4"></div>
                        <div class="col-md-4">
                            <button class="btn btn-secondary">Assign Teacher</button>
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
