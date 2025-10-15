<x-app-layout>
    @section('title')
        {{$sectionClass->name}} trobleshoot issues
    @endsection
    @section('breadcrumb')
       {{Breadcrumbs::render('dashboard')}}
    @endsection
    @section('content')
        <div class="card shadow">
            <div class="card-body">
                <div class="card-header text text-center h4 shadow">{{$sectionClass->name}} reported Issues</div>
                <table class="table">
        <thead>
            <tr>
                <th>S/N</th>
                <th>STATUS</th>
                <th>MESSAGE</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach($sectionClass->classReports() as $classReport)
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{$classReport['status']}}</td>
                    <td>{{$classReport['message']}}</td>
                    <td>
                    <a href="{{route('dashboard.section.trobleshoot.issue.fixed',[$sectionClass->id,$classReport['status']])}}">
                        <button class="btn btn-info">Fixed this Problem</button>
                        </a></td>
                </tr>
            @endforeach
        </tbody>
        </table>
            </div>
        </div>
    @endsection
    
</x-app-layout>
