<x-app-layout>
    @section('title')
        {{$sectionClass->name}} of {{$sectionClass->currentSession()->name}} Academic Session Result Summary
    @endsection
    @section('breadcrumb')
       {{Breadcrumbs::render('dashboard')}}
    @endsection
    @section('content')
   
    <div class="card shadow">
        <div class="card-body">
            <div class="card-header shadow h4">
            <b>{{$sectionClass->name}} of {{$sectionClass->currentSession()->name}} Academic Session  Result Summary</b> 
            <a href="{{route('dashboard.section.class.result.report',[$sectionClass->id])}}"><button class="btn btn-secondary">View Report Sheets</button></a>
            </div><br>
            <div class="row">
            
            @foreach($sectionClass->sectionClassSubjects as $sectionClassSubject)
                @foreach($sectionClassSubject->availableResultUploads($session->id, $term->id) as $result)
                <div class="col-md-3"><br>
                        <div class="card shadow">
                            <div class="card-body">
                            <p><b>Session :</b>{{$result->currentSession()->name}}</p>
                            <p><b>Term :</b> {{$result->term->name ?? 'Not Available'}}</p>
                            <p><b>Subject :</b> {{$result->sectionClassSubjectTeacher->sectionClassSubject->name ?? 'Not Available'}}</p>
                            <p><b>Teacher :</b> {{$result->sectionClassSubjectTeacher->teacher->user->name ?? 'Not Available'}}</p>
                                <table class="table">
                                    <tr>
                                    <td>A</td>
                                    <td>{{$result->gradeCount('A')}}</td>
                                    <td>{{$result->gradePercentage('A')}}%</td>
                                    </tr>
                                    <tr>
                                    <td>B</td>
                                    <td>{{$result->gradeCount('B')}}</td>
                                    <td>{{$result->gradePercentage('B')}}%</td>
                                    </tr>
                                    <tr>
                                    <td>C</td>
                                    <td>{{$result->gradeCount('C')}}</td>
                                    <td>{{$result->gradePercentage('C')}}%</td>
                                    </tr>
                                    <tr>
                                    <td>D</td>
                                    <td>{{$result->gradeCount('D')}}</td>
                                    <td>{{$result->gradePercentage('D')}}%</td>
                                    </tr>
                                    <tr>
                                    <td>E</td>
                                    <td>{{$result->gradeCount('E')}}</td>
                                    <td>{{$result->gradePercentage('E')}}%</td>
                                    </tr>
                                    <tr>
                                    <td>F</td>
                                    <td>{{$result->gradeCount('F')}}</td>
                                    <td>{{$result->gradePercentage('F')}}%</td>
                                    </tr>
                                    <tr>
                                    <td>Absent</td>
                                    <td>{{$result->gradeCount('Absent')}}</td>
                                    <td>{{$result->gradePercentage('Absent')}}%</td>
                                    </tr>
                                </table>
                                <a href="{{route('dashboard.section.class.subject.result.summary.detail',[$result->id])}}"<button class="btn btn-primary">View Detail</button></a>
                                <a href="{{route('dashboard.section.class.subject.result.summary.delete',[$result->id])}}"><button class="btn btn-danger" onclick="return confirm('Are you sure you want ot delete this result entirely')">Delete</button></a>
                            </div>
                        </div>
                </div><br>
                @endforeach
            @endforeach
            </div>
        </div>
    </div>
        
    @endsection
    
</x-app-layout>
