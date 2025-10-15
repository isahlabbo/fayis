
  
<div class="row mt-4">
        
    @foreach(App\Models\Section::all() as $section)
    <div class="col-md-3 mb-4" style="border-radius: 10px !important;">
    <a href="">
        <div class="card-body shadow text text-center" style="border-radius: 10px !important;">
            <h5 class="text text-primary center"><i class="fas fa-university"></i> {{$section->name}}</h5>
            <h6 class="text text-primary">
                <p>Fees: 0</p>
                <p>Payments: 0</p>
                <p>Receipts: 0</p>
                <p>Reports: 0</p>
            </h5>
        </div>
    </a>
    </div>
    @endforeach
      
</div>
