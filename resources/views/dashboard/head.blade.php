
  
<div class="row mt-4">
        
       
        <div class="col-md-3 mb-4" style="border-radius: 10px !important;">
        <a href="{{route('section.index')}}">
                <div class="card-body shadow text text-center" style="border-radius: 10px !important;">
                        <h5 class="text text-primary center">Sections</h5>
                        <h5 class="text text-primary"><i class="fas fa-university"></i> {{count(App\Models\Section::all())}}</h5>
                </div>
        </a>
        </div>
        <div class="col-md-3 mb-4" style="border-radius: 10px !important;">
        <a href="{{route('section.index')}}">
                <div class="card-body shadow text text-center" style="border-radius: 10px !important;">
                        <h5 class="text text-primary center">Teachers</h5>
                        <h5 class="text text-primary"><i class="fas fa-user-tie"></i> {{count(App\Models\Teacher::all())}}</h5>
                </div>
        </a>
        </div>

        <div class="col-md-3 mb-4" style="border-radius: 10px !important;">
        <a href="{{route('section.index')}}">
                <div class="card-body shadow text text-center" style="border-radius: 10px !important;">
                        <h5 class="text text-primary center">Subjects</h5>
                        <h5 class="text text-primary"><i class="fas fa-book"></i> {{count(App\Models\Subject::all())}}</h5>
                </div>
        </a>
        </div>

        <div class="col-md-3 mb-4" style="border-radius: 10px !important;">
        <a href="{{route('section.index')}}">
                <div class="card-body shadow text text-center" style="border-radius: 10px !important;">
                        <h5 class="text text-primary center">Applications</h5>
                        <h5 class="text text-primary"><i class="fas fa-file-signature"></i> 0</h5>
                </div>
        </a>
        </div>

        <div class="col-md-3 mb-4" style="border-radius: 10px !important;">
        <a href="{{route('section.index')}}">
                <div class="card-body shadow text text-center" style="border-radius: 10px !important;">
                        <h5 class="text text-primary center">Admissions</h5>
                        <h5 class="text text-primary"><i class="fas fa-user-graduate"></i> 0</h5>
                </div>
        </a>
        </div>

        <div class="col-md-3 mb-4" style="border-radius: 10px !important;">
        <a href="{{route('section.index')}}">
                <div class="card-body shadow text text-center" style="border-radius: 10px !important;">
                        <h5 class="text text-primary center">Payments</h5>
                        <h5 class="text text-primary"><i class="fas fa-credit-card"></i> 0</h5>
                </div>
        </a>
        </div>

        <div class="col-md-3 mb-4" style="border-radius: 10px !important;">
        <a href="{{route('section.index')}}">
                <div class="card-body shadow text text-center" style="border-radius: 10px !important;">
                        <h5 class="text text-primary center">Computers</h5>
                        <h5 class="text text-primary"><i class="fas fa-laptop"></i> 0</h5>
                </div>
        </a>
        </div>

        <div class="col-md-3 mb-4" >
        <a href="{{route('section.index')}}">
                <div class="card-body shadow text text-center" style="border-radius: 10px !important;" >
                        <h5 class="text text-primary center">Parents</h5>
                        <h5 class="text text-primary"><i class="fas fa-laptop"></i> 0</h5>
                </div>
        </a>
        </div>

        
      
</div>
