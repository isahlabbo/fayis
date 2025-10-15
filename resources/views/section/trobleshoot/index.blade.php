<x-app-layout>
    @section('title')
        {{$section->name}} trobleshoot issues
    @endsection
    @section('breadcrumb')
       {{Breadcrumbs::render('dashboard')}}
    @endsection
    @section('content')
        <div class="card shadow">
            <div class="card-body">
                <div class="card-header text text-center h4 shadow">{{$section->name}} Classes reported Issues</div>
                <table class="table">
        <thead>
            <tr>
                <th>S/N</th>
                <th>CLASS</th>
                <th>REPORT</th>
                
            </tr>
        </thead>
        <tbody>
            @foreach($section->sectionClasses as $sectionClass)
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{$sectionClass->name}}</td>
                    <td>{{count($sectionClass->classReports())}} Found</td>
                    <td>
                        <a href="{{route('dashboard.section.trobleshoot.class',[$sectionClass->id])}}">
                        <button class="btn btn-info">View Issues</button>
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
        </table>
            </div>
        </div>
    @endsection
    
</x-app-layout>
