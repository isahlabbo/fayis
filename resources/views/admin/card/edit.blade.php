<div class="modal fade" id="edit_{{$request->id}}" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"> Edit ID Card</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="{{route('admin.card.update',$request)}}" method="post" enctype="multipart/form-data">
          @csrf
            <div class="form-group">
                <label for="">Select Section</label>
                <select name="section" id="" class="form-control">
                    @if($request->section)
                    <option value="{{$request->section->id}}">{{$request->section->name}}</option>
                    @else
                    <option value="">Select Section</option>
                    @endif
                    @foreach(App\Models\Section::all() as $section)
                    <option value="{{$section->id}}">{{$section->name}}</option>    
                    @endforeach
                    <option value="{{count(App\Models\Section::all())+1}}">General</option>
                </select>
            </div>
            <div class="form-group">
                <label for="">Position</label>
                <select name="position" id="" class="form-control">
                    <option value="{{$request->position}}">{{$request->position}}</option>
                    <option value="Head of School">Head of School</option>
                    <option value="Asst. Head of School Admin">Asst. Head of School Admin</option>
                    <option value="Asst. Head of School Academics">Asst. Head of School Academics</option>
                    <option value="Sectional Head">Sectional Head</option>
                    <option value="Admission Officer">Admission Officer</option>
                    <option value="Asst. Admission Officer">Asst. Admission Officer</option>
                    <option value="Cashier">Cashier</option>
                    <option value="Exam Officer">Exam Officer</option>
                    <option value="Class Teacher">Class Teacher</option>
                    <option value="Sisco Staff">Sisco Staff</option>
                    <option value="Subject Teacher">Subject Teacher</option>
                    <option value="Cleaner">Cleaner</option>
                    <option value="Nany">Nany</option>
                    <option value="Security">Security</option>
                </select>
            </div>
            <!-- staff ID -->
            <div class="form-group">
                <label for="">Change your signature</label>
                <input type="file" name="signature" class="form-control"> 
            </div>
            
            <div class="form-group">
                <label for="">Reason for Applying</label>
                <textarea name="reason" id="" cols="30" rows="3" class="form-control" placeholder="Enter reason for applying ID card">{{$request->reason}}</textarea>
            </div>

            <div class="form-group">
                <button class="btn btn-secondary">Save Changes</button>
            </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
