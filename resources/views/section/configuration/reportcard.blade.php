<x-app-layout>
    @section('title')
        {{config('app.name')}} report card configuration
    @endsection
    @section('breadcrumb')
       {{Breadcrumbs::render('dashboard')}}
    @endsection
    @section('content')
    
    <div class="row">
    <div class="col-md-12">
    <div class="card shadow">
        <div class="card-body">
        <div class="card-header text text-bold text-center shadow"><b>{{strtoupper(config('app.name'))}} REPORT CARD CONFIGURATION</b></div><br>
        <div class="row">

                        <!-- effective trait start -->
                        <div class="col-md-5">
                        @foreach($sections as $section)
                            <div class="col-md-12">
                            <table class="table-bordered" style="width: 100%; height: 20px;">
                                <thead class="text text-center">
                                    <tr>
                                        <th class="shadow">{{strtoupper($section->name)}} AFFECTIVE TRAITS</th>
                                        <th><button class="btn btn-primary" data-toggle="modal" data-target="#affectiveTrait">New</button></th>
                                        <th></th>
                                        @include('section.configuration.affectivetrait.register')
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($section->affectiveTraits->where('status',1) as $affectiveTrait)
                                    <tr>
                                        <td>{{$affectiveTrait->name}}</td>
                                        <td class="text-center"><button class="btn btn-info" data-toggle="modal" data-target="#affectiveTrait_{{$affectiveTrait->id}}">Edit</button></td>
                                        <td class="text-center">
                                            <a href="{{route('dashboard.section.configuration.reportcard.affectivetrait.delete',[$affectiveTrait->id])}}">    
                                                <button class="btn btn-danger">Delete</button>
                                            </a>
                                        </td>
                                    </tr>
                                    @include('section.configuration.affectivetrait.edit')
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-12"><br></div>
                        @endforeach
                        </div>
                        <!-- effective trait end -->


                        <!-- psychomotor and rest start -->
                                    <div class="col-md-7">
                                        <div class="row">
                                            @foreach($sections as $section)
                                            <div class="col-md-12">
                                            <table class="table-bordered" style="width: 100%; height: 20px;">
                                                <thead class="text text-center">
                                                    <tr>
                                                        <th class=" shadow">{{strtoupper($section->name)}} PSYCHOMOTOR</th>
                                                        <th><button class="btn btn-primary" data-toggle="modal" data-target="#psychomotor">New</button></th>
                                                        <th></th>
                                                    </tr>
                                                    @include('section.configuration.psychomotor.register')
                                                </thead>
                                                <tbody>
                                                    @foreach($section->psychomotors->where('status',1) as $psychomotor)
                                                        <tr>
                                                            <td>{{$psychomotor->name}}</td>
                                                            <td class="text-center"><button class="btn btn-info" data-toggle="modal" data-target="#psychomotor_{{$psychomotor->id}}">Edit</button></td>
                                                            <td class="text-center">
                                                            <a href="{{route('dashboard.section.configuration.reportcard.psychomotor.delete',[$psychomotor->id])}}">    
                                                            <button class="btn btn-danger">Delete</button></a>
                                                        </td>
                                                        </tr>
                                                        @include('section.configuration.psychomotor.edit')
                                                    @endforeach
                                            </tbody>
                                       </table>
                                    </div>
                                    <div class="col-md-12"><br></div>
                                    @endforeach
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <p  class="text-center"><b></b></p>
                                        <table class="table-bordered text-center" style="width: 100%; height: 20px;">
                                            <thead>
                                                <tr>
                                                    <th>SCALE</th>
                                                    <th>REMARK</th>
                                                    <th>PERCENT</th>
                                                    <th>GRADE</th>
                                                    <th></th>
                                                    <th><button data-toggle="modal" data-target="#newRemark" class="btn btn-secondary">New Remark</button></th>
                                                    @include('section.configuration.remark.create')
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($remarkScales as $remarkScale)
                                                <tr>
                                                    <td>{{$remarkScale->scale}}</td>
                                                    <td>{{$remarkScale->remark}}</td>
                                                    <td>{{$remarkScale->percent}}</td>
                                                    <td>{{$remarkScale->grade}}</td>
                                                    <td><button data-toggle="modal" data-target="#remark_{{$remarkScale->id}}" class="btn btn-secondary">Edit</button></td>
                                                    <td><a href="{{route('dashboard.section.configuration.reportcard.remark.delete',$remarkScale->id)}}">
                                                    <button onclick="return confirm('Are you sure, you want delete this remark')" class="btn btn-primary">Delete</button></a></td>
                                                </tr>
                                                @include('section.configuration.remark.edit')
                                                @endforeach
                                            </tbody>
                                       </table>
                                    </div>
                                    
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <p  class="text-center"><b>KEY TO GRADING</b></p>
                                        <table class="table-bordered text-center" style="width: 100%; height: 20px;">
                                        <thead>
                                            <tr>
                                                <th>GRADE</th>
                                                <th>FROM</th>
                                                <th>TO</th>
                                                <th></th>
                                                <th>
                                                    <button data-toggle="modal" data-target="#newGrade" class="btn btn-secondary">New Grade</button>
                                                </th>
                                                @include('section.configuration.grade.create')
                                            </tr>
                                        </thead>    
                                        <tbody>
                                            @foreach($gradeScales as $gradeScale)
                                                <tr>
                                                    <td>{{$gradeScale->grade}}</td>
                                                    <td>{{$gradeScale->from}}</td>
                                                    <td>{{$gradeScale->to}}</td>
                                                    <td><button data-toggle="modal" data-target="#grade_{{$gradeScale->id}}" class="btn btn-secondary">Edit</button></td>
                                                    <td><a href="{{route('dashboard.section.configuration.reportcard.grade.delete',$gradeScale->id)}}">
                                                    <button onclick="return confirm('Are you sure, you want delete this grade')" class="btn btn-primary">Delete</button></a></td>
                                                </tr>
                                                @include('section.configuration.grade.edit')
                                            @endforeach
                                        </tbody>
                                       </table>
                                    </div>
                                </div>
                            </div>
                        <!-- psychomotor and rest start -->
                    </div>
                    <!-- accessment end -->
        </div>
    </div>
    </div>
    <div class="card">
    <div class="card-body">
    @php
        $letter = App\Models\AdmissionLetter::find(1);
        if($letter){
            $letter = App\Models\AdmissionLetter::create([]);
        }
    @endphp
    <div class="offer" style="page-break-inside: avoid;">
    <div class="row">
    <div class="col-md-1"></div>
    <div class="col-md-10">
        <!-- address start -->
        <div class="row">
            <div class="col-md-8 text text-left" style="line-height: 2.5;">
            <img src="{{asset('images/instituteLogo.JPG')}}" alt=""><br>
                <h5 class="text" style="line-height: 2.5;">{{config('app.title')}}</h5>
                <h5 class="text" style="line-height: 2.5;">{{config('app.address')}}</h5>
                <h5 class="text" style="line-height: 2.5;">STUDENT SECTION</h5>
                <h5 class="text" style="line-height: 2.5;">{{config('app.email')}}</h5>
                <h5 class="text" style="line-height: 2.5;">{{config('app.contact')}}</h5>
                <h5 class="text" style="line-height: 2.5;">{{date('d M, Y')}}.</h5>
            </div>
            <div class="col-md-4 text" style="line-spacing: 2;">
            <img src="{{asset(config('app.logo'))}}">
            </div>
        </div>
        <!-- address end -->
        
        <div class="col-md-12">
            <br>
            <br>
            <br>
        </div>
        <u><h5 class="text text-center"><b><a href="#" data-toggle="modal" data-target="#heading">{{$letter->heading}}</a> Class Name </b></h5></u>
        @include('section.configuration.letter.heading')
        <br>
        <p style="color:black;">Dear; Student Name's Guardian</p>
        <p style="text-align: justify; line-height: 2.5; color: black;">
        <a href="#" data-toggle="modal" data-target="#introduction_start">{{$letter->introduction_start}}</a>
            <b><i>Student Class</i></b> 
            <a href="#" data-toggle="modal" data-target="#introduction_contenue">{{$letter->introduction_contenue}}</a> section duration in year <a href="#" data-toggle="modal" data-target="#introduction_end">{{$letter->introduction_end}}</a> <b><i>Student Admission No</i></b>
        </p>
        
        <p style="text-align: justify; line-height: 2.5; color: black;">
            <b>Note,</b> <a href="#" data-toggle="modal" data-target="#payment_note_start">{{$letter->payment_note_start}}</a> <b><i>#fee amount</i></b> <a href="#" data-toggle="modal" data-target="#payment_note_contenue">{{$letter->payment_note_contenue}}</a> <b># fee amount/2</b> <a href="#" data-toggle="modal" data-target="#payment_note_end">{{$letter->payment_note_end}}</a>
        </p>
        
        <p style="text-align: justify; line-height: 2.5; color: black;">
            <b>Congratulation</b> <a href="#" data-toggle="modal" data-target="#congratulatory_note">{{$letter->congratulatory_note}}</a>
        </p>
        <div class="col-md-12">
            
        </div>
        <div id="sign" class="text text-center">
            <p style="text-align: center; line-height: 2.5; color: black;">SIGN:</p>
            <br>
            <p style="text-align: center; color: black;">SECTION HEAD TEACHER</h4></p>
        </div>
        
    </div>
    </div>
</div>
    </div>
    </div>
    </div>   
        
    @endsection
    
</x-app-layout>
