<x-app-layout>
    @section('title')
        {{$sectionClassSubject->subject->name}} subject teacher allocation
    @endsection
    
    @section('content')
    <div class="row">
    <div class="col-md-2"></div>
    <div class="col-md-8">
    <div class="card shadow">
        <div class="card-body">
        <div class="card-header text text-bold"><b>Edit {{$sectionClassSubject->subject->name}} Subjcet Teacher of {{$sectionClassSubject->sectionClass->name}}</b></div><br>
        <form action="{{route('section.class.subject.allocation.update',[$sectionClassSubject->id])}}" method="post">
                @csrf
                @method('PUT')
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
