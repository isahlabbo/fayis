


<li class="dropdown ml-3">
    <a href="#inventory" class="dropbtn fw-bold">
        <i class="fas fa-boxes"></i> Inventory
        <i class="fa fa-caret-down"></i>
    </a>
    <div class="dropdown-content">
        <a href="{{ route('finance.inventory.view') }}">
            <i class="fas fa-boxes"></i> Items
        </a>
        <a href="{{ route('finance.inventory.stock') }}">
            <i class="fas fa-box-open"></i> Stock
        </a>
        <a href="{{ route('finance.inventory.categories') }}">
            <i class="fas fa-tags"></i> Categories
        </a>
        <a href="{{ route('finance.inventory.sales') }}">
            <i class="fas fa-shopping-cart"></i> Sales
        </a>
        <a href="{{ route('finance.inventory.rents') }}">
            <i class="fas fa-hand-holding"></i> Rents
        </a>
        
    </div>
</li>
<li class="dropdown ml-3">
    <a href="#payments" class="dropbtn fw-bold">
        <i class="fas fa-credit-card"></i> Payments
        <i class="fa fa-caret-down"></i>
    </a>
    <div class="dropdown-content">
        <a href="{{ route('finance.payments.report') }}">
            <i class="fas fa-chart-line"></i> Payment Reports
        </a>
        <a href="{{ route('finance.payments.unpaid') }}">
            <i class="fas fa-exclamation-triangle"></i> Unpaid Reports
        </a>
    </div>
</li>
<li class="dropdown ml-3">
    <a href="#statistics" class="dropbtn fw-bold">
        <i class="fas fa-chart-pie"></i> Statistics
        <i class="fa fa-caret-down"></i>
    </a>
    <div class="dropdown-content">
        <a href="{{ route('patron.statistics.students') }}">
            <i class="fas fa-chart-area"></i> Students
        </a>
        <a href="{{ route('patron.statistics.teachers') }}">
            <i class="fas fa-chart-area"></i> Teachers
        </a>

    </div>
</li>

