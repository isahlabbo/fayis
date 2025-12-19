@extends('layouts.app')

    @section('title')
         analysis view
    @endsection
    @section('breadcrumb')
       
    @endsection
    @section('content')
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-info">
                    @if($analysisType == 1)
                        Teacher Effectiveness Index
                    @elseif($analysisType ==2)
                        Termly Subject Evaluation
                    @elseif($analysisType ==3)
                        Term vs Class Average Performance
                    @elseif($analysisType ==4)
                        Teacher vs Subject–Class Performance Comparison
                    @elseif($analysisType ==5)
                        Subject Distribution Analysis
                        <ul>
                        <li><b>Mean:</b> Average score of all students. Higher is better.</li>
                        <li><b>Median:</b> Middle score when all scores are ordered.</li>
                        <li><b>Mode:</b> Most frequently occurring score.</li>
                        <li><b>Min Score:</b> Lowest score achieved in class.</li>
                        <li><b>Max Score:</b> Highest score achieved in class.</li>
                        <li><b>Range:</b> Difference between highest and lowest scores.</li>
                        <li><b>Variance:</b> Spread of scores around the mean. Higher = more variation.</li>
                        <li><b>Standard Deviation:</b> Consistency of scores. Lower = students are performing similarly.</li>
                        </ul>
                    @endif
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div style="width: 100%; max-width: 900px; margin: 0 auto;">
                    {!! $chart->container() !!}
                </div>
                <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

                {{ $chart->script() }}
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-12">
                @include($dataTable)
            </div>
        </div>
    @endsection