<x-app-layout>
    @section('title')
        {{config('app.name')}} students
    @endsection
    @section('breadcrumb')
       {{Breadcrumbs::render('dashboard.student')}}
    @endsection
    
    @section('content')
        <div class="card shadow">
            <div class="card-body">
                <div class="row">
                <div class="col-md-10"></div>
                    <div class="col-md-2">
                        @if($student->picture)
                            <img src="{{$student->profileImage()}}" alt="" height="120" width="120" class="rounded">
                        @else
                            <img src="{{asset('assets/images/user.jpg')}}" width="120" height="120" class="rounded" alt="">
                        @endif
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-4">
                    <div class="card shadow">
                        <div class="card-header text-center shadow h5">Personnel Information</div>
                        <div class="card-body">
                        <table style="height: 30px;">
                            <tr>
                                <td><b>SECTION: </b></td>
                                <td>{{$student->activeSectionClass()->section->name}}</td>
                            </tr>
                            <tr>
                                <td><b>CLASS: </b></td>
                                <td>{{$student->activeSectionClass()->name}}</td>
                            </tr>
                        
                            <tr>
                                <td><b>ADMISSION NO: </b></td>
                                <td>{{$student->admission_no}}</td>
                            </tr>
                            <tr>
                                <td><b>STUDENT NAME: </b></td>
                                <td>{{$student->name}}</td>
                            </tr>

                            <tr>
                                <td><b>REGISTERED AT: </b></td>
                                <td>{{$student->created_at}}</td>
                            </tr>
                        
                            <tr>
                                <td><b>GUARDIAN NAME: </b></td>
                                <td>{{$student->guardian->name}}</td>
                            </tr>
                            <tr>
                                <td><b>GUARDIAN EMAIL: </b></td>
                                <td>{{$student->guardian->email}}</td>
                            </tr>
                            <tr>
                                <td><b>GUARDIAN PHONE: </b></td>
                                <td>{{$student->guardian->phone}}</td>
                            </tr>
                            <tr>
                                <td><b>GUARDIAN ADDRESS: </b></td>
                                <td>{{$student->guardian->address}}</td>
                            </tr>
                            
                        </table>
                        </div>
                    </div>
                        
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header text-center shadow h5">Result Information</div>
                            <div class="card-body">
                            
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header text-center shadow h5">Payment Information</div>
                            <div class="card-body">
                            
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
</x-app-layout>
