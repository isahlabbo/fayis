



<li class="nav-item ml-3"><a class="dropbt" href="{{route('admin.card.index')}}"><i class="fas fa-send"></i> Card Request </a></li>


<li class="dropdown ml-3">
    <a href="#users" class="dropbtn fw-bold">
        <i class="fas fa-user-tie"></i> Users <i class="fa fa-caret-down"></i>
    </a>
    <div class="dropdown-content" >
        <a  href="{{route('admin.user.index',['teachers'])}}"><i class="fas fa-user-graduate"></i> Teachers</a>
        <a  href="{{route('admin.user.index',['administrators'])}}"><i class="fas fa-user-shield"></i> Administrators</a>
        <a  href="{{route('admin.user.index',['other_staff'])}}"><i class="fas fa-user"></i> Other Staff</a>
    </div>  
</li>
