<x-app-layout>
    @section('title')
        {{config('app.name')}} student report card
    @endsection
    @section('breadcrumb')
       {{Breadcrumbs::render('dashboard.result.student',$student)}}
    @endsection
    @section('content')
           <div class="row">
                <div class="col-md-2"></div>
                <div class="col-md-1"><button class="btn btn-secondary btn-block" id="print" onclick="printContent('report');" >Print</button></div>
            </div><br>
            <div id="report">
                @foreach($student->sectionClassStudents as $sectionClassStudent)
                    @foreach($sectionClassStudent->sectionClassStudentTerms as $sectionClassStudentTerm)<br><br>
                        @if(count($sectionClassStudentTerm->studentResults)>0)
                            @include('section.class.student.result.reportcard.view')
                        @endif
                    @endforeach
                @endforeach
            </div>
    @endsection
    
</x-app-layout>
