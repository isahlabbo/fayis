<x-app-layout>
    @section('title')
        sections
    @endsection
    @section('breadcrumb')
       
    @endsection
    @section('content')
        
        
        <div class="row mt-4">
        
        @foreach($sections as $section)
        <div class="col-md-3" style="a:hover{text-decoration: none;}">
        <a href="{{route('section.classes',[$section->id])}}">
                <div class="card-body shadow text text-center">
                        <h5 class="text text-primary center">{{$section->name}}</h5>
                        <h6 class="text text-primary"><i class="fas fa-home"></i> {{count($section->sectionClasses)}} Classes</h6>
                        <p class="text text-right"><button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                        <button class="btn btn-sm btn-outline-warning"><i class="fas fa-pen"></i></button></p>
                </div>
        </a>
        </div>
        @endforeach 
       </div>

    @endsection
    
</x-app-layout>
