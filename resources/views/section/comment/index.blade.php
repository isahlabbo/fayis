<x-app-layout>
    @section('title')
        {{config('app.name')}} register new teacher
    @endsection
    @section('breadcrumb')
       {{Breadcrumbs::render('dashboard.comment')}}
    @endsection
    @section('content')
        <div>
        <button class="btn btn-secondary" id="print" onclick="printContent('report');" >Print</button>
        <a href="{{route('dashboard.comment.view')}}"><button class="btn btn-primary">View Coments</button></a>
        
        </div>
        
        <div id="report">
       
            <div class="card shadow" style="page-break-inside: avoid;">
                <div class="card-body">
                <p class="text text-center"><b>Teacher Comment</b></a></p>
                    <table class="table table-sm">
                        <tbody>
                        @foreach($teacherComments as $teacherComment)
                            <tr >
                                <td style="width: 50px;">{{$teacherComment->id}}</td>
                                <td>{{$teacherComment->name}}</td>
                                <td>{{$teacherComment->gender=='1' ? 'Male' : 'Female'}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    
                    <p class="text text-center"><b>Head Teacher Comment</b></a></p>
                    <table class="table table-sm">
                        <tbody>
                        @foreach($headTeacherComments as $headTeacherComment)
                            <tr>
                                <td style="width: 50px;">{{$headTeacherComment->id}}</td>
                                <td>{{$headTeacherComment->name}}</td>
                                <td>{{$headTeacherComment->gender=='1' ? 'Male' : 'Female'}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
    
        
    @endsection
    </div>
</x-app-layout>
