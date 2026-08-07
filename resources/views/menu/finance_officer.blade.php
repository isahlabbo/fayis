@foreach(App\Models\Section::all() as $section)
<li class="dropdown ml-3">
    <a href="#finance" class="dropbtn fw-bold">
        <i class="fas fa-coins"></i> {{$section->name}} 
        <i class="fa fa-caret-down"></i>
    </a>
    <div class="dropdown-content">
        <a href="{{route('finance.fees.classes',[$section->id])}}">
            <i class="fas fa-file-invoice"></i> Fees Setting
        </a>
        <a href="{{route('finance.payments.classes',[$section->id, 'school'])}}">
            <i class="fas fa-credit-card"></i> School Fees
        </a>
        <a href="{{route('finance.payments.classes',[$section->id, 'pta'])}}">
            <i class="fas fa-credit-card"></i> PTA Fees
        </a>
        <a href="{{route('finance.payments.classes',[$section->id, 'sisco'])}}">
            <i class="fas fa-credit-card"></i> SISCO Fees
        </a>
        <a href="{{ route('finance.payments.report', ['section' => $section->id]) }}">
            <i class="fas fa-chart-line"></i> Reports
        </a>
    </div>
</li>
@endforeach
<li class="dropdown ml-3">
    <a href="#inventory" class="dropbtn fw-bold">
        <i class="fas fa-boxes"></i> Inventory
        <i class="fa fa-caret-down"></i>
    </a>
    <div class="dropdown-content">
        <a href="{{ route('finance.inventory.categories') }}">
            <i class="fas fa-tags"></i> Categories
        </a>
        <a href="{{ route('finance.inventory.view') }}">
            <i class="fas fa-boxes"></i> Items
        </a>
        <a href="{{ route('finance.inventory.stock') }}">
            <i class="fas fa-box-open"></i> Stock
        </a>
        <a href="{{ route('finance.inventory.sales') }}">
            <i class="fas fa-shopping-cart"></i> Sales
        </a>
        <a href="{{ route('finance.inventory.rents') }}">
            <i class="fas fa-hand-holding"></i> Rents
        </a>
    </div>
</li>