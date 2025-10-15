<div class="modal fade" id="newComment" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add Comment</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="{{route('dashboard.comment.add')}}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="row from-group">
                <div class="col-md-4"><label for="">Comment</label></div>
                <div class="col-md-8">
                <textarea type="text" name="comment" rols="3" cols="30"  class="form-control" placeholder="Write some comment"></textarea></div>
            </div><br>
            <div class="row from-group">
                <div class="col-md-4"><label for="">Comment Type</label></div>
                <div class="col-md-8">
                <select name="type" id="" class="form-control">
                    <option value="">Type</option>
                    <option value="1">Class Teacher Comment</option>
                    <option value="2">Head Teacher Comment</option>
                </select>
                </div>
            </div><br>
            <div class="row from-group">
                <div class="col-md-4"><label for="">Gender For</label></div>
                <div class="col-md-8">
                <select name="gender" id="" class="form-control">
                    <option value="">Gender</option>
                    <option value="2">Female</option>
                    <option value="1">Male</option>
                </select>
                </div>
            </div><br>
            <button class="btn btn-primary">ADD</button>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>