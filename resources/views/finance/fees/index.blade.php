<x-app-layout>
    @section('title')
        {{$sectionClass->name}} fees
    @endsection
    
    
    @section('content')
            @foreach($sectionClass->sectionClassFees as $sectionClassFee)
               @include('finance.fees.add') 
                    <h6 class="text text-primary m-4">{{$sectionClassFee->fee->name}} of {{$sectionClass->name}}</h6>
                    <table class="table table-striped table-sm">
                        <thead>
                            <tr>
                                <th>DESCRIPTION</th>
                                <th>AMOUNT</th>
                                <th>GENDER</th>
                                <th>TERM</th>
                                <th><button data-toggle="modal" data-target="#add_{{$sectionClassFee->id}}" class="btn btn-sm btn-outline-primary">Add Fees Item</button></th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($sectionClassFee->sectionClassFeeItems as $sectionClassFeeItem)
                            @include('finance.fees.edit')
                            <tr>
                                <td>{{$sectionClassFeeItem->description}}</td>
                                <td>{{$sectionClassFeeItem->amount}}</td>
                                <td>{{$sectionClassFeeItem->gender->name}}</td>
                                <td>{{$sectionClassFeeItem->term->name}}</td>
                                <td>
                                    <button data-toggle="modal" data-target="#edit_{{$sectionClassFeeItem->id}}" class="btn btn-sm btn-outline-secondary"><i class="fas fa-pen"></i></button>
                                    <a href="{{route('finance.fees.deleteItem', [$sectionClassFeeItem->id])}}" onclick="return confirm('Are you sure you want delete this fees item?')" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                            
                        @endforeach
                        </tbody>
                    </table>
            @endforeach
    @endsection
</x-app-layout>
