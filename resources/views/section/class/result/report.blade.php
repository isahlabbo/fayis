<x-app-layout>
    @section('title')
        {{config('app.name')}} {{$sectionClass->name}} report card
    @endsection
    @section('breadcrumb')
       {{Breadcrumbs::render('dashboard')}}
    @endsection
    @section('content')
        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-2"><button class="btn btn-secondary btn-block" id="print" onclick="printContent('report');" >Print</button></div>
        </div>
        <div id="report">
        @foreach($sectionClass->sectionClassStudents->where('status','Active') as $sectionClassStudent)
            @foreach($sectionClassStudent->sectionClassStudentTerms->where('status','Active') as $sectionClassStudentTerm)<br><br>
                @php
                    $student = $sectionClassStudent->student;
                @endphp
                @include('section.class.student.result.reportcard.view')
            @endforeach
        @endforeach
        </div>
    @endsection
    
</x-app-layout>
