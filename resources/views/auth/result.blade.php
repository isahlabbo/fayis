<x-guest-layout>
    @section('title')
       
    @endsection
    
    @section('content')
        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-2"><button class="btn btn-secondary btn-block" id="print" onclick="printContent('report');" >Print</button></div>
        </div>
        <div id="report">
            @php
                $student = $studentTerm->sectionClassStudent->student;
                $sectionClassStudent = $studentTerm->sectionClassStudent;
                $sectionClassStudentTerm = $studentTerm;
            @endphp
            @include('section.class.student.result.reportcard.view')
        </div>
    @endsection
    
</x-app-layout>
