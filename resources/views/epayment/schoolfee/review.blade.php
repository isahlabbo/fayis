<x-app-layout>
    @section('title')
        e-payment
    @endsection
    
    
    @section('content')
        <div class="row">
        @foreach(App\Models\SectionClass::all() as $sectionClass)
        <div class="col-md-12">
            <div class="card-body shadow mb-2">
                @foreach(App\Models\Term::all() as $term)
                <p class="text-center"><b>{{$sectionClass->name}} School Fee Review for {{$term->name}}</b></p>
                <table class="table">
                    <thead>
                        <th>STATE</th>
                
                        <th>DAY MALE</th>
                        <th>DAY FEMALE</th>
                        <th>BORDING MALE</th>
                        <th>BORDING FEMALE</th>
                        <th>STAFF CHILDREN</th>
                    </thead>
                <tbody>
                    @foreach(App\Models\State::all() as $state)
                        @if($state->name == 'Kebbi'|| $state->name == 'Niger'|| $state->name == 'Sokoto' || $state->name == 'Zamfara')
                        <tr>
                            <td>{{$state->name}}</td>
                            <td>{{$sectionClass->stateSchoolFee($term, $state, 2, 1)}}</td>
                            <td>{{$sectionClass->stateSchoolFee($term, $state, 2, 2)}}</td>
                            <td>{{$sectionClass->stateSchoolFee($term, $state, 1, 1)}}</td>
                            <td>{{$sectionClass->stateSchoolFee($term, $state, 1, 2)}}</td>
                            <td></td>
                            
                        </tr>
                        @endif
                    @endforeach
                    <tr>
                        <td>Others</td>
                        <td>{{$sectionClass->stateSchoolFee($term, $state, 2, 1)}}</td>
                        <td>{{$sectionClass->stateSchoolFee($term, $state, 2, 2)}}</td>
                        <td>{{$sectionClass->stateSchoolFee($term, $state, 1, 1)}}</td>
                        <td>{{$sectionClass->stateSchoolFee($term, $state, 1, 2)}}</td>
                        <td></td>
                    </tr>
                </tbody>
                </table>
                @endforeach
            </div>
        </div>
        @endforeach
        </div>
    @endsection
</x-app-layout>
