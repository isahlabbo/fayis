<x-app-layout>
    @section('title')
        {{$sectionClass->name}} 
    @endsection
    @section('breadcrumb')
       
    @endsection
    @section('content')
        
    <div class="row mt-4">
        <div class="col-md-3">
            <a href="">
                <div class="card-body shadow text text-center mb-4">
                    <h5 class="text text-primary center">Class Teacher</h5>
                    <h6 class="text text-primary"><i class="fas fa-user-tie"></i>

                    @if($allocation = $sectionClass->activeClassTeacher())
                    {{$allocation->teacher->user->name}}
                    @else
                    <a href="{{route('section.class.teacher.create',[$sectionClass->id])}}" class="btn btn-sm btn-outline-warning">Assign Teacher</a>
                    @endif
                </h6>
                </div>
            </a>
        </div>

        <div class="col-md-3">
            <a href="{{route('section.class.student.index',[$sectionClass->id])}}">
                <div class="card-body shadow text text-center mb-4">
                    <h5 class="text text-primary center">Students</h5>
                    <h5 class="text text-primary"><i class="fas fa-user-tie"></i> {{count($sectionClass->sectionClassStudents)}}</h6>
                </div>
            </a>
        </div>

        <div class="col-md-3">
            <a href="{{route('section.class.subject.index',[$sectionClass->id])}}">
                <div class="card-body shadow text text-center mb-4">
                    <h5 class="text text-primary center">Subjects</h5>
                    <h6 class="text text-primary"><i class="fas fa-book"></i> {{count($sectionClass->sectionClassSubjects)}}</h6>
                </div>
            </a>
        </div>

        <div class="col-md-3">
            <a href="">
                <div class="card-body shadow text text-center mb-4">
                    <h5 class="text text-primary center">Results</h5>
                    <h6 class="text text-primary"><i class="fas fa-medal"></i> </h6>
                </div>
            </a>
        </div>

        <div class="col-md-3">
            <a href="">
                <div class="card-body shadow text text-center mb-4">
                    <h5 class="text text-primary center">Payments</h5>
                    <h6 class="text text-primary"><i class="fas fa-credit-card"></i></h6>
                </div>
            </a>
        </div>
        
       </div>
    @endsection
    
</x-app-layout>
