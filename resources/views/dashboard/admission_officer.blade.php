
  
<div class="row mt-4">
        
        <div class="col-md-3 mb-4" style="border-radius: 10px !important;">
        <a href="{{route('admission.student.index')}}">
            <div class="card-body shadow text text-center" style="border-radius: 10px !important;">
                <h5 class="text text-primary center">Students</h5>
                <h5 class="text text-primary"><i class="fas fa-user-graduate"></i> {{count(App\Models\Student::all())}}</h5>
            </div>
        </a>
        </div>
        
        <div class="col-md-3 mb-4" style="border-radius: 10px !important;">
        <a href="">
                <div class="card-body shadow text text-center" style="border-radius: 10px !important;">
                        <h5 class="text text-primary center">Application</h5>
                        <h5 class="text text-primary"><i class="fas fa-file-signature"></i> 0</h5>
                </div>
        </a>
        </div>
        <div class="col-md-3 mb-4" style="border-radius: 10px !important;">
        <a href="">
                <div class="card-body shadow text text-center" style="border-radius: 10px !important;">
                        <h5 class="text text-primary center">Admission</h5>
                        <h5 class="text text-primary"><i class="fas fa-comments"></i> 0</h5>
                </div>
        </a>
        </div>

        <div class="col-md-3 mb-4" style="border-radius: 10px !important;">
        <a href="">
                <div class="card-body shadow text text-center" style="border-radius: 10px !important;">
                        <h5 class="text text-primary center">Interviews</h5>
                        <h5 class="text text-primary"><i class="fas fa-id-card"></i> 0</h5>
                </div>
        </a>
        </div>

        <div class="col-md-3 mb-4" style="border-radius: 10px !important;">
        <a href="{{route('section.index')}}">
            <div class="card-body shadow text text-center" style="border-radius: 10px !important;">
                <h5 class="text text-primary center">Confirmation</h5>
                <h5 class="text text-primary"><i class="fas fa-check-circle"></i> 0</h5>
            </div>
        </a>
        </div>

        
</div>
