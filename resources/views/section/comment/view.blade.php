<x-app-layout>
    @section('title')
        {{config('app.name')}} register new teacher
    @endsection
    @section('breadcrumb')
       {{Breadcrumbs::render('dashboard.comment.view')}}
    @endsection
    @section('content')
        
        <div class="card shadow" style="page-break-inside: avoid;">
            <div class="card-body">
            <div class="card-header shadow h5 text-center"><b> Teacher Comment</b></div>
                <table class="table">
                    <thead>
                        <tr>
                            <th>S/N</th>
                            <th>Name</th>
                            <th>Gender</th>
                            <th><button class="btn btn-primary" data-toggle="modal" data-target="#newComment">New Comment</button>
        @include('section.comment.create')</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($teacherComments as $teacherComment)
                        <tr >
                            <td style="width: 50px;">{{$teacherComment->id}}</td>
                            <td>{{$teacherComment->name}}</td>
                            <td>{{$teacherComment->gender=='1' ? 'Male' : 'Female'}}</td>
                            <td><button data-toggle="modal" data-target="#remark_{{$teacherComment->id}}" class="btn btn-secondary">Edit</button></td>
                            <td>
                            <a href="{{route('dashboard.comment.teacher.delete',$teacherComment->id)}}">
                            <button onclick="return confirm('Are you sure you want to delete this teacher remark')" class="btn btn-danger">Delete</button>
                            </a></td>
                        </tr>
                        @include('section.comment.teacherCommentEdit')
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <br>
        <div class="card shadow">
            <div class="card-body">
                <div class="card-header shadow h5 text-center"><b>Head Teacher Comment</b></div>
                <table class="table">
                    <thead>
                        <tr>
                            <th>S/N</th>
                            <th>Name</th>
                            <th>Gender</th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($headTeacherComments as $headTeacherComment)
                        <tr>
                            <td style="width: 50px;">{{$headTeacherComment->id}}</td>
                            <td>{{$headTeacherComment->name}}</td>
                            <td>{{$headTeacherComment->gender=='1' ? 'Male' : 'Female'}}</td>
                            <td><button data-toggle="modal" data-target="#headTeacherRemark_{{$headTeacherComment->id}}" class="btn btn-secondary">Edit</button></td>
                            <td>
                            <a href="{{route('dashboard.comment.headteacher.delete',$headTeacherComment->id)}}">
                            <button onclick="return confirm('Are you sure you want to delete this head teacher remark')" class="btn btn-danger">Delete</button>
                            </a></td>
                        </tr>
                        @include('section.comment.headTeacherCommentEdit')
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endsection
</x-app-layout>
