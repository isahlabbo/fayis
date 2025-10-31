<x-app-layout>
    @section('title')
        {{$sectionClass->name}} of {{$sectionClass->currentSession()->name}} Academic Session Result Summary
    @endsection
    @section('breadcrumb')

    @endsection

    @section('content')
   
    <div class="card shadow">
        <div class="card-body">
            <div class="card-header shadow h4 mb-4">
            {{$sectionClass->name}} of {{$sectionClass->currentSession()->name}} Academic Session  Result Submition
           @php
            $totalSubjects = count($sectionClass->sectionClassSubjects);
            
            $submitted = 0;
            foreach($sectionClass->subjectResultUploads()['uploaded'] as $result){
                if($result->status == 1){
                    $submitted = $submitted + 1;
                }
            }
            $remaining = $totalSubjects - $submitted;
           @endphp
            @if($remaining < 1)
            <a href="{{route('exam.upload.result.publish',[$sectionClass->id])}}" onclick="confirm('Are you sure you want to publish this result? Please note that publishing indicates you have reviewed and verified the result and consider it accurate and ready to be viewed by the public, including parents and guardians.')" class="btn btn-primary">Publish Result</a>
            @endif    
        </div>
            <!-- display progress bar showing the upload in % -->
             
             <div class="progress" style="height: 40px; font-size:20px;">
                @php
                $percentage = ($submitted / $totalSubjects) * 100;
                @endphp
                <div class="progress-bar" role="progressbar" style="width: {{$percentage}}%;" aria-valuenow="{{$percentage}}" aria-valuemin="0" aria-valuemax="100">{{$percentage}}%</div>
            </div>
            <p class="mt-2">Submitted: {{$submitted}} / {{$totalSubjects}}
            </p>

            
            <div class="row">
            
            @foreach($sectionClass->subjectResultUploads()['uploaded'] as $result)
            @if($result->status == 1)
                <div class="col-md-4"><br>
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
                                <a href="{{route('teacher.class.result.details',[$result->id])}}"><button class="btn btn-primary">View Detail</button></a>
                                <a href="{{route('teacher.class.result.return',[$result->id])}}" class="btn btn-outline-danger">Return for Correction</a>
                            </div>
                        </div>
                </div><br>
                @endif
            @endforeach
            </div>
        </div>
    </div>
        
    @endsection
    
</x-app-layout>
