<x-app-layout>
    @section('title')
        {{config('app.name')}} {{$sectionClass->name}} view accessment
    @endsection
    @section('breadcrumb')
       {{Breadcrumbs::render('dashboard')}}
    @endsection
    @section('content')
        
        <div id="report">
        <table class="table table-bordered table-triped table-responsive" >
            <thead>
                <tr>
                    <th>NAME</th>
                    <th>ADMISSION NO</th>
                    <th>DAYS SCHOOL OPEN</th>
                    <th>DAYS PRESENT</th>
                    <th>DAYS ABSENT</th>
                    <th>HEAD TEACHER REMARK</th>
                    <th>TEACHER REMARK</th>
                    @foreach($sectionClass->section->affectiveTraits as $affectiveTrait)
                        <th>{{strtoupper($affectiveTrait->name)}}</th>
                    @endforeach
                    @foreach($sectionClass->section->psychomotors as $psychomotor)
                        <th>{{strtoupper($psychomotor->name)}}</th>
                    @endforeach
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($sectionClass->sectionClassStudents->where('status','Active') as $sectionClassStudent)
                    @foreach($sectionClassStudent->sectionClassStudentTerms->where('status','Active') as $sectionClassStudentTerm)
                       @if($sectionClassStudentTerm->sectionClassStudentTermAccessment)
                        <tr>
                            <td>{{$sectionClassStudent->student->name}}</td>
                            <td>{{$sectionClassStudent->student->admission_no}}</td>
                            <td>{{$sectionClassStudentTerm->sectionClassStudentTermAccessment->days_school_open}}</td>
                            <td>{{$sectionClassStudentTerm->sectionClassStudentTermAccessment->days_present}}</td>
                            <td>{{$sectionClassStudentTerm->sectionClassStudentTermAccessment->days_absent}}</td>
                            <td>{{$sectionClassStudentTerm->sectionClassStudentTermAccessment->headTeacherComment->id ?? 'Not Available'}}</td>
                            <td>{{$sectionClassStudentTerm->sectionClassStudentTermAccessment->teacherComment->id ?? 'Not Available'}}</td>
                            @foreach($sectionClassStudentTerm->sectionClassStudentTermAccessment->sectionClassStudentTermAccessmentAffectiveTraits as $accessmentAffectiveTrait)    
                                <td>{{$accessmentAffectiveTrait->value}}</td>
                            @endforeach
                            @foreach($sectionClassStudentTerm->sectionClassStudentTermAccessment->sectionClassStudentTermAccessmentPsychomotors as $accessmentPsychomotor)    
                                <td>{{$accessmentPsychomotor->value}}</td>
                            @endforeach
                            <td>
                            <a href="{{route('dashboard.section.class.result.accessment.edit',[$sectionClass->id,$sectionClassStudentTerm->id])}}">
                            <button class="btn btn-secondary">Edit</button></a>
                            </td>
                        </tr>
                        @endif
                    @endforeach
                @endforeach
            </tbody>
        </table>
        </div>
    @endsection
    
</x-app-layout>
