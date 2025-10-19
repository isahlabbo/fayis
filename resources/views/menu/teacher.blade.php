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
        <a class="fw-bold" href="">{{$subject->sectionClassSubject->subject->name}}</a>
        @endforeach
    </div>  
</li>
<!-- my class -->
<li class="dropdown ml-3">
    <a href="#academics" class="dropbtn fw-bold">
        <i class="fas fa-chalkboard"></i> My Classes <i class="fa fa-caret-down"></i>
    </a>
    <div class="dropdown-content">
        @foreach(App\Models\SectionClassTeacher::where('teacher_id',Auth::user()->teacher->id)->get() as $class)
        <a class="fw-bold" href="">{{$class->sectionClass->name}}</a>
        @endforeach
    </div>
