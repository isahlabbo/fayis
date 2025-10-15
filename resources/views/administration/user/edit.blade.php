
<div class="modal fade" id="edit_{{$user->id}}" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">EDIT USER </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="{{route('dashboard.user.update',[$user->id])}}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" name="name" placeholder="Name" value="{{$user->name}}" class="input-group form-control">
            </div>
            <div class="form-group">
                <label for="">Email</label>
                <input type="email" name="email" placeholder="E-mail address" value="{{$user->email}}" class="input-group form-control">
            </div>
            <div class="form-group">
                <label for="">Change Password</label>
                <input type="password" name="password" placeholder="Change Password" value="{{old('password')}}" class="input-group form-control">
            </div>
            <div class="form-group">
                <label for="">User Role</label>
                <select name="role" id="" class="input-group form-control">
                <option value="{{$user->role}}">{{$user->role}}</option>
                <option value="admin">Admin</option>
                <option value="exam_officer">Exam Officer</option>
                <option value="admission_officer">Admission Officer</option>
                <option value="ict">ICT User</option>
                </select>
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