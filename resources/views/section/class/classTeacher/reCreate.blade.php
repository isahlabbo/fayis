<x-app-layout>
    @section('title')
        {{$sectionClassTeacher->sectionClass->name}} class teacher assign another one
    @endsection
    @section('breadcrumb')
       
    @endsection
    @section('content')
    <div class="row">
    <div class="col-md-2"></div>
    <div class="col-md-8">
    <div class="card shadow">
        <div class="card-body">
        <div class="card-header text text-bold"><b>Change Class Teacher To {{$sectionClassTeacher->sectionClass->name}}</b></div><br>
        <form action="{{route('section.class-teacher.register',[$sectionClassTeacher->sectionClass->id])}}" method="post">
                @csrf
                <div class="form-group row">
                    <div class="col-md-4"><label for="">Teacher</label></div>
                       <input type="hidden" value="{{$sectionClassTeacher->sectionClass->id}}" name="sectionClassId">
                       <input type="hidden" value="{{$sectionClassTeacher->sectionClass->id}}" name="sectionClassId">
                        <div class="col-md-8">
                            <select name="teacher" id="" class="form-control">
                                <option value="">{{$sectionClassTeacher->teacher->user->name ?? 'Select Teacher'}}</option>
                                @foreach($teachers as $teacher)
                                    @if($sectionClassTeacher->teacher && $sectionClassTeacher->teacher->user->id != $teacher->user->id)
                                        <option value="{{$teacher->id}}">{{$teacher->user->name}}</option>
                                    @endif
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
