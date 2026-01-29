<div class="modal fade" id="edit_{{$user->id}}" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"> Edit User</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="{{route('admin.user.update',[$user->id])}}" method="post">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" name="name" id="name" class="form-control" value="{{$user->name}}">  
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" class="form-control" value="{{$user->email}}">  
            </div>
            <div class="form-group">
                <label for="role">Role</label>
                <select name="role" id="role" class="form-control">
                    <option value="">Select Role</option>
                    <option value="admin" {{$user->role == 'admin' ? 'selected' : ''}}>Admin</option>
                    <option value="head" {{$user->role == 'head' ? 'selected' : ''}}>Head of school</option>
                    <option value="teacher" {{$user->role == 'teacher' ? 'selected' : ''}}>Teacher</option>
                    <option value="exam_officer" {{$user->role == 'exam_officer' ? 'selected' : ''}}>Exam Officer</option>
                    <option value="patron" {{$user->role == 'patron' ? 'selected' : ''}}>Patron</option>
                    <option value="admission_officer" {{$user->role == 'admission_officer' ? 'selected' : ''}}>Admission Officer</option>
                </select>  
            </div>

            <div class="form-group">
              <label for="status">Status</label>
              <select name="status" id="status" class="form-control">
                  <option value="">Select Status</option>
                  <option value="Active" {{$user->status == 'Active' ? 'selected' : ''}}>Active</option>
                  <option value="Inactive" {{$user->status == 'Inactive' ? 'selected' : ''}}>Inactive</option>
              </select>  
            </div>

            <div class="form-group">
              <label for="password">Password (Leave blank to keep current password)</label>
              <input type="password" name="password" id="password" class="form-control">  
            </div>

            <div class="form-group">
              <label for="password_confirmation">Confirm Password</label>
              <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">  
            </div>
            <div class="form-group">
                <button class="btn btn-primary">Update</button>
            </div>
        </form>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>