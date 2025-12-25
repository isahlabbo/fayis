<x-app-layout>
    @section('title')
        Result Access Code for {{$section->name}}   
    @endsection
    @section('breadcrumb')
       
    @endsection
    @section('content')
            <div class="h3">
            <b>Result Access Code for {{$section->name}}</b>
            </div>
            <div class="row">
                @foreach($section->sectionClasses as $sectionClass)
                    @foreach($sectionClass->sectionClassStudents as $sectionClassStudent)
                        @php
                            $sectionClassStudentTerm = $sectionClassStudent->currentStudentTerm();
                        @endphp
                        @if($sectionClassStudentTerm)
                        
                            <div class="col-md-3" style="border:1px solid #ccc;">
                                <!-- avoid page break -->
                                    <div class="card-body" style="page-break-inside: avoid; margin-bottom:10px; margin-top:10px;">
                                    <h6><b>{{$sectionClassStudent->student->name}}</b></h6>    
                                    <table>
                                        <tr>
                                            <td>Class</td>
                                            <td>{{$sectionClass->name}}</td>
                                        </tr>
                                        <tr>
                                            <td>Session</td>
                                            <td>{{$sectionClassStudentTerm->academicSessionTerm->academicSession->name}}</td>
                                        </tr>
                                        <tr>
                                            <td>Term</td>
                                            <td>{{$sectionClass->name}}</td>
                                        </tr>
                                        <tr>
                                            <td>Code</td>
                                            <td>{{$sectionClassStudentTerm->access_code}}</td>
                                        </tr>
                                        <tr>
                                            <td>link</td>
                                            <td>https://fayis.ng/result/check</td>
                                        </tr>
                                    </table>
                                </div>  
                        </div>
                        @endif
                    @endforeach
                @endforeach
            </div>
        
    @endsection
    
</x-app-layout>
