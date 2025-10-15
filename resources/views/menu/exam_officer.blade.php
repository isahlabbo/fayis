
@foreach(App\Models\Section::all() as $section)
<li class="dropdown ml-3">
    <a href="#academics" class="dropbtn fw-bold">
        <i class="fas fa-graduation-cap"></i> {{$section->name}} Results 
        <i class="fa fa-caret-down"></i>
    </a>
    <div class="dropdown-content">
        <a href="">
            <i class="fas fa-upload"></i> Uploaded
        </a>
        <a href="">
            <i class="fas fa-hourglass-half"></i> Pending
        </a>
        <a href="">
            <i class="fas fa-file-alt"></i> Report Card
        </a>
        <a href="">
            <i class="fas fa-clipboard-check"></i> Assessment
        </a>
    </div>
</li>
@endforeach