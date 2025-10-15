<x-app-layout>
    @section('title')
        {{config('app.name')}} clss fee record management
    @endsection
    
    @section('content')
    <div class="row">
    <div class="col-md-12">
    <div class="text-primary"><b>{{$sectionClass->name}} FEE INFORMATION MANAGEMENT</b></div><br>
    
    <div class="card shadow">
        <div class="card-body">
        <table class="table">
            <thead>
                <tr>
                    <th>S/N</th>
                    <th>FEE NAME</th>
                    <th>FEE AMOUNT</th>
                    <th>TERM</th>
                    <th>GENDER</th>
                    <th>STUDENT</th>
                    <th><button data-toggle="modal" data-target="#addFee" class="btn btn-secondary">ADD FEE</button></th>
                    @include('section.payment.class.fee.add')
                </tr>
            </thead>
            <tbody>
               @foreach($sectionClass->sectionClassPayments as $sectionClassPayment)
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{$sectionClassPayment->name}}</td>
                    <td>{{$sectionClassPayment->amount}}</td>
                    <td>{{$sectionClassPayment->term->name}}</td>
                    <td>
                        {{$sectionClassPayment->gender->name ?? 'Both'}}
                    </td>
                    <td>
                        {{$sectionClassPayment->studentType->name}}
                    </td>
                    <td>
                        <button data-toggle="modal" data-target="#fee_{{$sectionClassPayment->id}}" class="btn btn-secondary">Edit</button>
                         @include('section.payment.class.fee.edit')
                        <a href="{{route('dashboard.section.class.fee.delete',[$sectionClass->id, $sectionClassPayment->id])}}">
                        <button class="btn btn-warning" onclick="return confirm('Are you sure, you want to delete this fee form the class')">Delete</button>
                        </a>
                    </td>

                </tr>
                @endforeach
            </tbody>
        </table>
        </div>
    </div>
    </div>
    </div>   
        
    @endsection
    
</x-app-layout>
