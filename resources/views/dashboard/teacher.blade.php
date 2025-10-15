<div class="card shadow">
<div class="card-body">
<div class="card-header shadow h5 text-center"><b>{{Auth::user()->teacher->sectionClassTeachers->where('status','Active')[0]->sectionClass->name}} {{Auth::user()->teacher->currentSession()->currentSessionTerm()->term->name}} of {{Auth::user()->teacher->currentSession()->name}} Academic Session Result Management</b></div>
</div>
<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>S/N</th>
            <th>SUBJECT</th>
            <th>UPLOAD ATTEMPTS</th>
            <th>DOWNLOADS ATTEMPTS</th>
            <th></th>
            <th></th>
            <th></th>
        </tr>
    </thead>
    <tbody>
    @foreach(Auth::user()->teacher->sectionClassTeachers->where('status','Active') as $sectionClassTeacher)
        @foreach($sectionClassTeacher->sectionClass->sectionClassSubjects as $sectionClassSubject)
            <tr>
                <td>{{$loop->iteration}}</td>
                <td>{{$sectionClassSubject->name}}</td>
                <td></td>
                <td></td>
                <td><a href="#"><button class="btn btn-success">View Result</button></a></td>
                <td><a href="{{route('dashboard.teacher.download.scoresheet',[$sectionClassSubject->activeSectionClassSubjectTeacher()->id])}}"><button class="btn btn-secondary">Download Score Sheet</button></a></td>
                <td><a href="{{route('dashboard.teacher.upload.scoresheet',[$sectionClassSubject->activeSectionClassSubjectTeacher()->sectionClassSubject->id])}}"><button class="btn btn-primary">Upload Result</button></a></td>
            </tr>
        @endforeach
    @endforeach
    </tbody>
</table>
</div>
