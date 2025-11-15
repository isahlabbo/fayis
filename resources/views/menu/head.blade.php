<li class="dropdown ml-3">
    <a href="#academics" class="dropbtn fw-bold">
        <i class="fas fa-comment"></i> Notifications <i class="fa fa-caret-down"></i>
    </a>
    <div class="dropdown-content">
        <a href=""><i class="fas fa-comment"></i>Teaching Staff</a>
        <a href=""><i class="fas fa-comment"></i>Supporting Staff</a>
        <a href=""><i class="fas fa-comment"></i>Administors</a>
        <a href=""><i class="fas fa-comment"></i>Parents</a>
        
    </div>  
</li>
<li class="dropdown ml-3">
    <a href="#academics" class="dropbtn fw-bold">
        <i class="fas fa-medal"></i> Results <i class="fa fa-caret-down"></i>
    </a>
    <div class="dropdown-content">
        @foreach(App\Models\AcademicSession::all() as $session)
        <a class="fw-bold" href="">{{$session->name}}</a>
        @endforeach
    </div>  
</li>

<li class="dropdown ml-3">
    <a href="#academics" class="dropbtn fw-bold">
        <i class="fas fa-university"></i> Sections <i class="fa fa-caret-down"></i>
    </a>
    <div class="dropdown-content">
        @foreach(App\Models\Section::all() as $section)
        <a class="fw-bold" href="{{route('section.classes',[$section->id])}}">{{$section->name}}</a>
        @endforeach
        <a class="fw-bold" href="{{route('section.index')}}">Sections</a>
    </div>  
</li>

<li class="dropdown ml-3">
    <a href="#academics" class="dropbtn fw-bold">
        <i class="fas fa-building"></i> Adminstration <i class="fa fa-caret-down"></i>
    </a>
    <div class="dropdown-content">
        <a href=""><i class="fas fa-clock"></i>Academic Terms</a>
        <a href="{{route('administration.user.index')}}"><i class="fas fa-user-tie"></i> Administrators</a>
        <a href="{{route('administration.session.index')}}"><i class="fas fa-calendar"></i> Academic Calendars</a>
        <a href="{{route('administration.teacher.index')}}"><i class="fas fa-chalkboard-teacher"></i> Teachers</a>
        <a href="{{route('administration.staff.index')}}"><i class="fas fa-chalkboard-teacher"></i> Other Staff</a>
        <a href="{{route('administration.card.index')}}"><i class="fas fa-id-card"></i> ID Card Requests</a>
    </div>  
</li>

<li class="dropdown ml-3">
    <a href="#academics" class="dropbtn fw-bold">
        <i class="fas fa-cog"></i> Configuration <i class="fa fa-caret-down"></i>
    </a>
    <div class="dropdown-content" >
        <a  href=""><i class="fas fa-file-signature"></i> Application</a>
        <a  href=""><i class="fas fa-envelope-open"></i> Admission Letter</a>
        <a  href="#"><i class="fas fa-credit-card"></i> School Fee</a>
        <a  href="#"><i class="fas fa-clipboard-check"></i> Report Sheet</a>
        <a  href=""><i class="fas fa-star-half-alt"></i> Grading</a>
        <a  href=""><i class="fas fa-star-half-alt"></i> Remarks</a>
        <a  href=""><i class="fas fa-comment"></i> Teachers Comments</a>
        <a  href=""><i class="fas fa-comment"></i> Head of School Comments</a>
        <a  href=""><i class="fas fa-comment"></i> Psychomotor</a>
        <a  href=""><i class="fas fa-comment"></i> Affective Traits</a>
    </div>  
</li>

