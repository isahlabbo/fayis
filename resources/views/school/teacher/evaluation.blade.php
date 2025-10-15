<x-app-layout>
    @section('title')
        {{config('app.name')}} Teachers
    @endsection
    @section('breadcrumb')
       {{Breadcrumbs::render('dashboard.teacher')}}
    @endsection
    @section('content')
        <div class="row">
                <div class="col-md-2"></div>
                <div class="col-md-1"><button class="btn btn-secondary btn-block" id="print" onclick="printContent('report');" >Print</button></div>
            </div><br>
        <div id="report">
        @foreach(App\Models\Section::all() as $section)
            @foreach($section->sectionClasses as $sectionClass)
            <div class="col-md-12">
                <h4 class="text-center">{{$sectionClass->name}}</h4>
            </div>
            @foreach(App\Models\Term::all() as $term)
                <table class="table">
                    <thead>
                        <tr>
                        @foreach($sectionClass->sectionClassSubjects as $subject)
                            <th>{{strtoupper($subject->name)}}</th>
                        @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            @foreach($sectionClass->sectionClassSubjects as $subject)
                                <td style="page-break-inside: avoid;">
                                    <table>
                                        <tr>
                                            <td>Percentage</td>
                                            <td>{{optional($subject->thisSessionTermResultUpload($session,$term))->percentageScoresOfAllStudents() ?? ''}}</td>
                                        </tr>
                                    </table>
                                </td>
                            @endforeach
                        </tr>
                    </tbody>    
                </table>
            @endforeach
            @endforeach
        @endforeach
        </div>
    @endsection
    
</x-app-layout>
