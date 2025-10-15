<x-app-layout>
    @section('title')
        {{$section->name}} fees
    @endsection
    
    @section('content')
    <div class="row">
        @foreach($section->sectionClasses as $sectionClass)
            <div class="col-md-3 mb-4" >
                <div class="card-body shadow" style="border-radius: 10px !important;">
                    <h5 class="text text-primary text-center mb-4"><i class="fas fa-home"></i> {{$sectionClass->name}}</h5>
                    @foreach($sectionClass->sectionClassFees as $sectionClassFee)
                        <p class="text text-secondary">{{$sectionClassFee->fee->name}}</p>
                        @if($sectionClassFee->fee->id == 1)
                        <ul>
                            @foreach(App\Models\Gender::all() as $gender)
                            <li class="text text-primary">{{$gender->name}}</li> 
                                <table>
                                    @foreach(App\Models\Term::all() as $term)
                                        <tr>
                                            <td>{{$term->name}} :</td>
                                            <td>{{$sectionClass->schoolFees($term->id, $gender->id)}}</td>
                                        </tr>
                                    @endforeach
                                </table>
                            @endforeach
                        </ul>
                        @else
                            @foreach(App\Models\Gender::all() as $gender)
                                <p class="text text-primary"><b>{{$gender->name}}:</b> {{$sectionClass->materialFees($gender->id)}}</p> 
                            @endforeach
                        @endif
                    @endforeach
                    <div class="text text-right">
                    <a href="{{route('finance.fees.index',[$sectionClass->id])}}" class="btn btn-sm btn-outline-danger"><i class="fas fa-cog"></i> Settings</a>
                    </div>
                </div>
            
            </div>
            
        @endforeach
    </div>
    @endsection
</x-app-layout>
