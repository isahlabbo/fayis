<div class="row">
    <div class="col-md-12">
        <p>{{\App\Models\Section::find(1)->currentSession()->name}} Class Performance Dashboard</p>
        <div style="width: 100%; max-width: 900px; margin: 0 auto;">
            {!! $chart->container() !!}
        </div>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        {{ $chart->script() }}
    </div>
</div>