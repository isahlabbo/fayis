<x-app-layout>
    @section('title')
        {{config('app.name')}} payment
    @endsection
    @section('breadcrumb')
       {{Breadcrumbs::render('dashboard')}}
    @endsection
    @section('content')
        @foreach($terms as $term)
        <div class="row">
        <div class="col-md-12">
        <div class="card shadow">
            <div class="card-body">
            <div class="card-header text text-bold text-center shadow"><b>SEARCH ALL {{$sectionClass->name}} PAYMENT FOR {{$term->name}}</b></div><br>
            <table class="table">
                <thead>
                    <tr>
                        <th>S/N</th>
                        <th>STUDENT NAME</th>
                        <th>GUARDIAN NAME</th>
                        <th>GUARDIAN PHONE</th>
                        <th>GUARDIAN ADDRESS</th>
                        <th>FEE</th>
                        <th>PAID AMOUNT</th>
                        <th>PENDING PAYMENT</th>
                        <th></th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                @foreach($sectionClass->sectionClassStudents->where('status','Active') as $sectionClassStudent)
                    <tr>
                        <td>{{$loop->iteration}}</td>
                        <td>{{$sectionClassStudent->student->name}}</td>
                        <td>{{$sectionClassStudent->student->guardian->name}}</td>
                        <td>{{$sectionClassStudent->student->guardian->phone}}</td>
                        <td>{{$sectionClassStudent->student->guardian->address}}</td>
                        <td><b>#</b>{{$sectionClassStudent->feeAmount($term)}}</td>
                        <td><b>#</b>{{$sectionClassStudent->paidAmount($term)}}</td>
                        <td><b>#</b>{{$sectionClassStudent->feeAmount($term)-$sectionClassStudent->paidAmount($term)}}</td>
                        <td>
                            @if($sectionClassStudent->paidAmount($term) > 0)
                            <a href="{{route('dashboard.payment.class.student.receipt',[$sectionClassStudent->id,$term->id])}}"><button class="btn btn-success">Reciept</button></a>
                            @endif
                        </td>
                        <td>
                            @if($sectionClassStudent->feeAmount($term)-$sectionClassStudent->paidAmount($term)>0)
                            <button data-toggle="modal" data-target="#payment_{{$sectionClassStudent->id.$term->id}}" class="btn btn-secondary">Add Payment</button>
                            @endif
                        </td>
                        
                    </tr>
                    @include('section.payment.class.student.addPayment')
                    @endforeach
                </tbody>
            </table>
            </div>
        </div>
        </div>
        </div>   
        @endforeach   
    @endsection
    
</x-app-layout>
