<div class="modal fade" id="headTeacherRemark_{{$headTeacherComment->id}}" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Edit Remark</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="{{route('dashboard.comment.headteacher.update',[$headTeacherComment->id])}}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="row from-group">
                <div class="col-md-4"><label for="">Remark</label></div>
                <div class="col-md-8">
                <textarea type="text" name="name" rols="3" cols="30"  class="form-control">{{$headTeacherComment->name}}</textarea></div>
            </div><br>
            <div class="row from-group">
                <div class="col-md-4"><label for="">Remark For</label></div>
                <div class="col-md-8">
                <select name="gender" id="" class="form-control">
                    <option value="{{$headTeacherComment->gender}}">{{$headTeacherComment->gender==1 ? 'Male' : 'Female'}}</option>
                    @if($headTeacherComment->gender == 1)
                        <option value="2">Female</option>
                    @else
                        <option value="1">Male</option>
                    @endif
                </select>
                </div>
            </div><br>
            <button class="btn btn-primary">update</button>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>