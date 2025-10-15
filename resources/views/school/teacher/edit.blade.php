<div class="modal fade" id="teacher_{{$teacher->id}}" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">{{$teacher->user->name}} Edit</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <form action="{{route('dashboard.teacher.update',[$teacher->id])}}" method="post">
      @csrf
      <div class="form-group row">
          <div class="col-md-3"><label for="">Name</label></div>
          <div class="col-md-9">
              <input type="text" class="form-control" name="name" value="{{$teacher->user->name}}" placeholder="Enter Teacher's Name">
          </div>
      </div>
      <div class="form-group row">
          <div class="col-md-3"><label for="">Phone</label></div>
          <div class="col-md-9">
              <input type="text" class="form-control" name="phone" value="{{$teacher->phone}}" placeholder="Enter Teacher's Phone number">
          </div>
      </div>
      <div class="form-group row">
          <div class="col-md-3"><label for="">Email</label></div>
          <div class="col-md-9">
              <input type="email" class="form-control" name="email" value="{{$teacher->user->email}}" placeholder="Enter Teacher's Email">
          </div>
      </div>
      <div class="form-group row">
          <div class="col-md-3"><label for="">Date Of Birth</label></div>
          <div class="col-md-9">
              <input type="date" class="form-control" name="date_of_birth" value="{{$teacher->date_of_birth}}" placeholder="Teacher's Date of birth">
          </div>
      </div>

      <div class="form-group row">
          <div class="col-md-3"><label for="">Address</label></div>
          <div class="col-md-9">
              <textarea class="form-control" name="address" placeholder="Enter Teacher's Home address" cols="30" rows="3" class="form-controller">{{$teacher->address}}</textarea>
          </div>
      </div>

      <div class="form-group row">
          <div class="col-md-3"><label for="">New Password</label></div>
          <div class="col-md-9">
              <input type="password" class="form-control" name="password">
          </div>
      </div>
      <div class="form-group row">
          <div class="col-md-3"><label for="">Confirm New Password</label></div>
          <div class="col-md-9">
              <input type="password" class="form-control" name="password_confirmation">
          </div>
      </div>
      <div class="form-group row">
          <div class="col-md-2"></div>
          <div class="col-md-9">
              <button class="btn btn-secondary">Update</button>
          </div>
      </div>    
  </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>