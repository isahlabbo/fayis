
@foreach(App\Models\Section::all() as $section)
<li class="dropdown ml-3">
    <a href="#admission" class="dropbtn fw-bold">
        <i class="fas fa-user-graduate"></i> {{$section->name}}
        <i class="fa fa-caret-down"></i>
    </a>
    <div class="dropdown-content">
        <a href="">
            <i class="fas fa-file-signature"></i> Applications
        </a>
        <a href="">
            <i class="fas fa-comments"></i> Interview
        </a>
        <a href="">
            <i class="fas fa-id-card"></i> Admission
        </a>
        <a href="">
            <i class="fas fa-check-circle"></i> Confirmation
        </a>
        <a href="{{route('admission.student.index')}}">
            <i class="fas fa-user-graduate"></i> Students
        </a>
    </div>
</li>
@endforeach