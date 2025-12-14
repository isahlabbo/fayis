<li class="dropdown ml-3">
    <a href="#academics" class="dropbtn fw-bold">
        <i class="fas fa-graduation-cap "></i> Results <i class="fa fa-caret-down"></i>
    </a>
    <div class="dropdown-content">
        @foreach(App\Models\AcademicSession::all() as $session)
        <a class="fw-bold" href=""> {{$session->name}}</a>
        @endforeach
    </div>  
</li>

<li class="dropdown ml-3">
    <a href="#academics" class="dropbtn fw-bold">
        <i class="fas fa-book"></i> Subjects <i class="fa fa-caret-down"></i>
    </a>
    <div class="dropdown-content">
        @foreach(App\Models\SectionClassSubjectTeacher::where('teacher_id',Auth::user()->teacher->id)->get() as $subject)
            @if($subject->sectionClassSubject && $subject->sectionClassSubject->status == 'Active')
            <a class="fw-bold" href="{{route('teacher.subject.index', $subject->id)}}">{{$subject->sectionClassSubject->subject->name}}</a>
            @endif
        @endforeach
    </div>  
</li>
<!-- my class -->
<li class="dropdown ml-3">
    <a href="#academics" class="dropbtn fw-bold">
        <i class="fas fa-chalkboard"></i> My Classes <i class="fa fa-caret-down"></i>
    </a>
    <div class="dropdown-content">
        @foreach(App\Models\SectionClassTeacher::where('teacher_id',Auth::user()->teacher->id)->get() as $classTeacher)
        <a class="fw-bold" href="{{route('teacher.class.index', [$classTeacher->id])}}">{{$classTeacher->sectionClass->name}}</a>
        @endforeach
    </div>
