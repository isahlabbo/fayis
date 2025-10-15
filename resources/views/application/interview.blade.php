<div class="modal fade" id="edit_{{$academicSession->id}}" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"> Edit {{$academicSession->name}} Academic Session</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="{{route('dashboard.session.update',[$academicSession->id])}}" method="post">
            @csrf
            <div class="form-group">
                <label for="english">English</label>
                <input type="date" name="english" id="english" class="form-control" value="{{old('english')}}">
            </div>
            
            <button class="btn btn-primary">Update</button>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>