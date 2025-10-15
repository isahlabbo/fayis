<x-app-layout>
    @section('title')
        {{$sectionClassSubject->name}} of {{$sectionClassSubject->sectionClass->name}}  Result Summary
    @endsection
    @section('breadcrumb')
       {{Breadcrumbs::render('dashboard.result.summary',$session, $term, $sectionClassSubject)}}
    @endsection
    @section('content')
    <div class="card shadow">
        <div class="card-body">
            <div class="card-header shadow h3">
            <b>{{$sectionClassSubject->name}} of {{$sectionClassSubject->sectionClass->name}}  Result Summary</b>
            </div><br>
            <div class="row">
            @foreach($sectionClassSubject->availableResultUploads($session->id,$term->id) as $result)
               <div class="col-md-3">
                    <div class="card shadow">
                        <div class="card-body">
                           <p><b>Session :</b></p>
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
                            </table>
                            <a href="{{route('dashboard.section.class.subject.result.summary.detail',[$result->id])}}"<button class="btn btn-primary">View Detail</button></a>
                            <a href="{{route('dashboard.section.class.subject.result.summary.delete',[$result->id])}}"<button class="btn btn-danger">Delete</button></a>
                        </div>
                    </div>
               </div>
            @endforeach
            </div>
        </div>
    </div>
        
    @endsection
    
</x-app-layout>
