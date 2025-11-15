
<div class="modal fade" id="newUser" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">REGISTER NEW USER TO ID CARD REQUEST </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="{{route('administration.card.register')}}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="">Staff Name</label>
                <input type="text" class="form-control" name="name" value="{{old('name')}}">
            </div>
            <div class="form-group">
                <label for="">Staff Email</label>
                <input type="email" class="form-control" name="email" value="{{old('email')}}">
            </div>
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
                    <option value="Academic Officer">Academic Officer</option>
                    <option value="Admission Ofiicer">Admission Ofiicer</option>
                    <option value="Exam Officer">Exam Officer</option>
                    <option value="Class Teacher">Class Teacher</option>
                    <option value="Subject Teacher">Subject Teacher</option>
                    <option value="Cleaner">Cleaner</option>
                    <option value="Game Master">Game Master</option>
                    <option value="Security">Security</option>
                    <option value="Nany">Nany</option>
                    <option value="Lab Technician">Lab Technician</option>
                </select>
            </div>
            <!-- staff ID -->
            <div class="form-group">
                <label for="">Staff ID (if applicable)</label>
                <input type="text" name="staffID"  class="form-control" placeholder="Enter your staff ID if applicable" value="FAYIS/STAFF/{{count(App\Models\User::all())}}"> 
            </div>
            
            <div class="form-group">
                <label for="">Reason for Applying</label>
                <textarea name="reason" id=""  cols="30" rows="5" class="form-control" placeholder="Enter reason for applying ID card">{{old('reason')}}</textarea>
            </div>

            <button class="btn btn-primary">Save Changes</button>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>