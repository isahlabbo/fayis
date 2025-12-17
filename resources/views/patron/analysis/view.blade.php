@extends('layouts.app')

    @section('title')
         analysis view
    @endsection
    @section('breadcrumb')
       
    @endsection
    @section('content')
       <div class="row">
            <div class="col-md-12">
                <div style="width: 100%; max-width: 900px; margin: 0 auto;">
                    {!! $chart->container() !!}
                </div>
                <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

                {{ $chart->script() }}
            </div>
       </div>
    @endsection