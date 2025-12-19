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