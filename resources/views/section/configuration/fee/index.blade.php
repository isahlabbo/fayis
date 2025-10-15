<x-app-layout>
    
    @section('title')
        school fee discount configuration
    @endsection
    @section('breadcrumb')
       {{Breadcrumbs::render('dashboard')}}
    @endsection
    @section('content')
    <p class="p-4">Welcome to school fee discount configuration page, remember any setting made on this page will alter the school fee of the affected student 
        <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#testFee">Test School Fee</button></p>
        @include('section.configuration.fee.test')
        @foreach(App\Models\Section::all() as $section)
        <div class="car-body shadow row">
            <div class="col-md-7">
                <p class="p-4">STATE BASED DISCOUNT FOR {{$section->name}} SECTION</p>
                <table class="table">
                    <thead>
                        <tr>
                            <th>S/N</th>
                            <th>STATE</th>
                            <th>FIRST TERM</th>
                            <th>SECOND TERM</th>
                            <th>THIRD TERM</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(App\Models\State::all() as $state)
                            @if(in_array($state->id, ['22','27','34','37']))
                            <tr>
                                <td>{{$loop->iteration}}</td>
                                <td><b>{{$state->name}}</b></td>
                                @foreach($state->neighboringStateDiscounts->where('section_id', $section->id) as $discount)
                                <td>{{number_format($discount->amount,2)}}</td>
                                @endforeach
                                <td><button data-toggle="modal" data-target="#update_{{$section->id.$state->id}}" class="btn btn-primary">Update</button></td>
                            </tr>
                            @endif
                            @include('section.configuration.fee.state')
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="col-md-5">
                <p class="p-4">STAFF CHILDREN DISCOUNT FOR {{strtoupper($section->name)}} SECTION</p>
                <table class="table">
                    <thead>
                        <tr>
                            <th>S/N</th>
                            <th>TERM</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($section->staffChildrenDiscounts as $staffChildrenDiscount)
                            <tr>
                                <td>{{$loop->iteration}}</td>
                                <td><b>{{$staffChildrenDiscount->term->name}}</b></td>
                                <td>{{number_format($staffChildrenDiscount->amount,2)}}</td>
                                <td><button data-toggle="modal" data-target="#staff_{{$staffChildrenDiscount->id}}" class="btn btn-primary">Update</button></td>
                            </tr>
                            @include('section.configuration.fee.staffchild')
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endforeach
    @endsection

</x-app-layout>