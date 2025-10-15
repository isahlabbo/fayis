<x-app-layout>
    @section('title')
        {{config('app.name')}} {{$sectionClass->name}} view accessment
    @endsection
    @section('breadcrumb')
       {{Breadcrumbs::render('dashboard')}}
    @endsection
    @section('content')
        
        <div class="">
            <form action="{{route('dashboard.section.class.result.accessment.update',[$sectionClass->id,$sectionClassStudentTerm->id])}}" method="post">
            @csrf
            <div class="row ">
                <div class="col-md-2 text-center">
                    @if($sectionClassStudentTerm->sectionClassStudent->student->picture)
                    <img src="{{$sectionClassStudentTerm->sectionClassStudent->student->profileImage()}}" alt="" height="200" width="170p" class="rounded">
                    @else
                    <img src="{{asset('assets/images/user.jpg')}}" width="200" height="170p" class="rounded" alt="">
                    @endif
                </div>
                <div class="col-md-4">
                @php
                    $sectionClassStudent = $sectionClassStudentTerm->sectionClassStudent;
                @endphp
                    <table style="width: 100%">
                        <tr>
                            <td style="width: 150px"><p class="mb-0">Admission No:</p></td>
                            <td><p class="mb-0 text-right"><b>{{$sectionClassStudent->student->admission_no}}</b></p></td>
                        </tr>
                        <tr>
                            
                            <td> <p class="mb-0">Student Name:</p></td>
                            <td><p class="mb-0 text-right"><b>{{$sectionClassStudent->student->name}}</b></p></td>
                        </tr>
                        <tr>
                            
                            <td><p class="mb-0">Sex:</p></td>
                            <td><p class="mb-0 text-right"><b>{{$sectionClassStudent->student->gender->name}}</b></p></td>
                        </tr>
                        <tr>
                            
                            <td><p class="mb-0">No in class:</p></td>
                            <td><p class="mb-0 text-right"><b>{{count($sectionClassStudent->sectionClass->sectionClassStudents->where('status','Active'))}}</b></p></td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-12"><br></div>
                    <div class="col-md-6 form-group">
                        <div class="row">
                            <div class="col-md-3">
                                <label for="">Head Teacher Comment</label>
                            </div>
                            <div class="col-md-9">
                                <select name="head_teacher_comment_id" id="" class="form-control">
                                    <option value="{{$sectionClassStudentTerm->sectionClassStudentTermAccessment->headTeacherComment->id}}">{{$sectionClassStudentTerm->sectionClassStudentTermAccessment->headTeacherComment->name}}</option>
                                    @foreach($headTeacherComments as $headTeacherComment)
                                        @if($headTeacherComment->id !=$sectionClassStudentTerm->sectionClassStudentTermAccessment->headTeacherComment->id)
                                            <option value="{{$headTeacherComment->id}}">{{$headTeacherComment->name}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 form-group">
                        <div class="row">
                            <div class="col-md-3">
                                <label for="">Teacher Comment</label>
                            </div>
                            <div class="col-md-9">
                                <select name="teacher_comment_id" id="" class="form-control">
                                    <option value="{{$sectionClassStudentTerm->sectionClassStudentTermAccessment->teacherComment->id}}">{{$sectionClassStudentTerm->sectionClassStudentTermAccessment->teacherComment->name}}</option>
                                    @foreach($teacherComments as $teacherComment)
                                        @if($teacherComment->id !=$sectionClassStudentTerm->sectionClassStudentTermAccessment->teacherComment->id)
                                            <option value="{{$teacherComment->id}}">{{$teacherComment->name}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 form-group">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="">Days School Open</label>
                            </div>
                            <div class="col-md-6">
                                <input type="number" name="days_school_open" value="{{$sectionClassStudentTerm->sectionClassStudentTermAccessment->days_school_open}}" id="" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 form-group">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="">Days Present</label>
                            </div>
                            <div class="col-md-6">
                                <input type="number" name="days_present" id="" value="{{$sectionClassStudentTerm->sectionClassStudentTermAccessment->days_present}}" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 form-group">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="">Days Absent</label>
                            </div>
                            <div class="col-md-6">
                                <input type="number" name="days_absent" value="{{$sectionClassStudentTerm->sectionClassStudentTermAccessment->days_absent}}" id="" class="form-control">
                            </div>
                        </div>
                    </div>
                @foreach($sectionClassStudentTerm->sectionClassStudentTermAccessment->sectionClassStudentTermAccessmentAffectiveTraits as $accessmentAffectiveTrait)    
                    <div class="col-md-3 form-group">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="">{{$accessmentAffectiveTrait->affectiveTrait->name}}</label>
                            </div>
                            <div class="col-md-6">
                                <select name="{{$accessmentAffectiveTrait->affectiveTrait->name}}" id="" class="form-control">
                                    <option value="{{$accessmentAffectiveTrait->value}}">{{$accessmentAffectiveTrait->value}}</option>
                                    @foreach($remarkScales as $remarkScale)
                                        @if($remarkScale->scale != $accessmentAffectiveTrait->value)
                                          <option value="{{$remarkScale->scale}}">{{$remarkScale->scale}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                @endforeach
                @foreach($sectionClassStudentTerm->sectionClassStudentTermAccessment->sectionClassStudentTermAccessmentPsychomotors as $accessmentPsychomotor)    
                    <div class="col-md-3 form-group">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="">{{$accessmentPsychomotor->Psychomotor->name}}</label>
                            </div>
                            <div class="col-md-6">
                                <select name="{{$accessmentPsychomotor->Psychomotor->name}}" id="" class="form-control">
                                    <option value="{{$accessmentPsychomotor->value}}">{{$accessmentPsychomotor->value}}</option>
                                    @foreach($remarkScales as $remarkScale)
                                        @if($remarkScale->scale != $accessmentPsychomotor->value)
                                          <option value="{{$remarkScale->scale}}">{{$remarkScale->scale}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                @endforeach
                <button class="btn btn-block btn-success">Update Accessment</button>
                </div>
            </form>
            <br>
            <br>
            <br>
            <br>
        </div>
    @endsection
    
</x-app-layout>
