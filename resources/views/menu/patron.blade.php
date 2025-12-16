
@foreach(App\Models\Section::all() as $section)
<li class="dropdown ml-3">
    <a href="#academics" class="dropbtn fw-bold">
        <i class="fas fa-graduation-cap"></i> {{$section->name}} 
        <i class="fa fa-caret-down"></i>
    </a>
    <div class="dropdown-content">
        <a href="{{route('patron.section.index',[$section->id])}}">
            <i class="fas fa-file-alt"></i> Performance
        </a>
        <a href="{{route('patron.analysis.teaching')}}">
            <i class="fas fa-file-alt"></i> Teaching Effectiveness
        </a>
        
    </div>
</li>
@endforeach

<li class="nav-item ml-3"><a class="dropbt" href="{{route('patron.analysis.teaching')}}"><i class="fas fa-sign-in-alt"></i> Teaching </a></li>

