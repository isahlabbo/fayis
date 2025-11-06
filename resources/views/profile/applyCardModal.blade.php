<div class="modal fade" id="applyCard" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"> Apply for ID Card</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="{{route('profile.cardRequest',[Auth::user()->id])}}" method="post">
          @csrf
            <div class="form-group">
                <label for="">Select Section</label>
                <select name="section" id="" class="form-control">
                    <option value="">Select Section</option>
                    @foreach(App\Models\Section::all() as $section)
                    <option value="{{$section->id}}">{{$section->name}}</option>    
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="">Position</label>
                <select name="position" id="" class="form-control">
                    <option value="">Select Position</option>
                    <option value="Head of School">Head of School</option>
                    <option value="Head of School">Academic Officer</option>
                    <option value="Head of School">Admission Ofiicer</option>
                    <option value="Head of School">Exam Officer</option>
                    <option value="Head of School">Class Teacher</option>
                    <option value="Head of School">Subject Teacher</option>
                    <option value="Head of School">None Teaching Staff</option>
                </select>
            </div>
            <!-- staff ID -->
            <div class="form-group">
                <label for="">Staff ID (if applicable)</label>
                <input type="text" name="staffID" class="form-control" placeholder="Enter your staff ID if applicable"> 
            </div>
            
            <div class="form-group">
                <label for="">Reason for Applying</label>
                <textarea name="reason" id="" cols="30" rows="5" class="form-control" placeholder="Enter reason for applying ID card"></textarea>
            </div>

            <div class="form-group">
                <button class="btn btn-secondary">Submit Application</button>
            </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
