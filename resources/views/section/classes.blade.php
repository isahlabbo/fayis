<x-app-layout>
    @section('title')
        {{$section->name}}
    @endsection
    @section('breadcrumb')
       
    @endsection
    @section('content')
        <div class="row mt-4">
        
        @foreach($section->sectionClasses as $sectionClass)
        <div class="col-md-3">
        <a href="{{route('section.class.index',[$sectionClass->id])}}">
                <div class="card-body shadow text text-center mb-4">
                    <h5 class="text text-primary center"><i class="fas fa-home"></i> {{$sectionClass->name}}</h5>
                    <h6 class="text text-primary"><i class="fas fa-user-tie"></i> {{count($sectionClass->sectionClassStudents)}}</h6>
                    <p class="text text-right">
                        <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                        <button class="btn btn-sm btn-outline-warning"><i class="fas fa-pen"></i></button>
                    </p>
                </div>
        </a>
        </div>
        @endforeach 
       </div>
    @endsection
    
</x-app-layout>
