@foreach(App\Models\Section::all() as $section)
<li class="dropdown ml-3">
    <a href="#finance" class="dropbtn fw-bold">
        <i class="fas fa-coins"></i> {{$section->name}} 
        <i class="fa fa-caret-down"></i>
    </a>
    <div class="dropdown-content">
        <a href="{{route('finance.fees.classes',[$section->id])}}">
            <i class="fas fa-file-invoice"></i> Fees
        </a>
        <a href="{{route('finance.payments.classes',[$section->id])}}">
            <i class="fas fa-credit-card"></i> Payments
        </a>
        <a href="">
            <i class="fas fa-receipt"></i> Receipts
        </a>
        <a href="">
            <i class="fas fa-chart-line"></i> Reports
        </a>
    </div>
</li>
@endforeach