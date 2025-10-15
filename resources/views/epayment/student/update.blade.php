
<div class="modal fade" id="update_{{$sectionClassStudent->id}}" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Update {{$sectionClassStudent->student->name}} Information</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="{{route('dashboard.epayment.update',[$sectionClassStudent->id])}}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="from-group mt-2">
                <label for="">State</label>
                <select name="state" id="" class="form-input form-control">
                <option value="{{$sectionClassStudent->student->lga->state->id ?? ''}}">{{$sectionClassStudent->student->lga->state->name ?? 'State'}}</option>
                @foreach(App\Models\State::all() as $state)
                <option value="{{$state->id}}">{{$state->name}}</option>
                @endforeach
                </select>
            </div>
            <div class="from-group mt-2">
                <label for="">Lga</label>
                <select name="lga" id="" class="form-input form-control">
                    <option value="{{$sectionClassStudent->student->lga->id ?? ''}}">{{$sectionClassStudent->student->lga->name ?? 'LGA'}}</option>
                    @if($sectionClassStudent->student->lga)
                        @foreach($sectionClassStudent->student->lga->state->lgas as $lga)
                        <option value="{{$lga->id}}">{{$lga->name}}</option>
                        @endforeach
                    @endif
                </select>
            </div>
            <div class="from-group mt-2">
                <label for="">Studen Type</label>
                <select name="student_type" id="" class="form-input form-control">
                    <option value="">Student Type</option>
                        @foreach(App\Models\StudentType::all() as $studentType)
                        <option value="{{$studentType->id}}">{{$studentType->name}}</option>
                        @endforeach
                </select>
            </div>
            <div class="from-group mt-2">
                <label for="">Gender</label>
                <select name="gender" id="" class="form-input form-control">
                    <option value="">Gender</option>
                        @foreach(App\Models\Gender::all() as $gender)
                        <option value="{{$gender->id}}">{{$gender->name}}</option>
                        @endforeach
                </select>
            </div>
            <div class="from-group mt-2">
                <label for="">Select Term/Terms to generate Invoice for</label><br>
            @foreach($sectionClassStudent->sectionclassStudentTerms as $sectionClassStudentTerm)
            
            {{$sectionClassStudentTerm->academicSessionTerm->term->name}} : <input type="checkbox" name="terms[]" id="" value="{{$sectionClassStudentTerm->id}}">
            
            @endforeach
            </div>
            <button class="btn btn-primary  mt-2">Update & Generate invoice</button>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>