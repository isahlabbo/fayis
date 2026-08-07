
  
<div class="row mt-4">
        
    @foreach(App\Models\Section::all() as $section)
    <div class="col-md-3 mb-4" style="border-radius: 10px !important;">
    <a href="{{ route('finance.payments.classes', [$section->id, 'school']) }}">
        <div class="card-body shadow text text-center" style="border-radius: 10px !important;">
            <h5 class="text text-primary center"><i class="fas fa-university"></i> {{$section->name}}</h5>
            <div class="text-start mt-3">
                <p class="mb-1"><strong>Payments:</strong> {{ number_format($section->paymentCount()) }}</p>
                <p class="mb-1"><strong>Paid Total:</strong> {{ number_format($section->paymentTotal(), 2) }}</p>
                <p class="mb-1"><strong>Sales:</strong> {{ number_format($section->salesCount()) }}</p>
                <p class="mb-1"><strong>Sales Total:</strong> {{ number_format($section->salesTotal(), 2) }}</p>
            </div>
        </div>
    </a>
    </div>
    @endforeach
      
</div>
