
<div class="modal fade" id="remark_{{$remarkScale->id}}" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Edit Remark</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="{{route('dashboard.section.configuration.reportcard.remark.update',[$remarkScale->id])}}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="row from-group">
                <div class="col-md-4"><label for="">Remark</label></div>
                <div class="col-md-8"><input type="text" name="remark" value="{{$remarkScale->remark}}" class="form-control"></div>
            </div><br>
            <div class="row from-group">
                <div class="col-md-4"><label for="">Scale</label></div>
                <div class="col-md-8"><input type="text" name="scale" value="{{$remarkScale->scale}}" class="form-control"></div>
            </div><br>
            <div class="row from-group">
                <div class="col-md-4"><label for="">Percent</label></div>
                <div class="col-md-8"><input type="number" name="percent" value="{{$remarkScale->percent}}" class="form-control"></div>
            </div><br>
            <div class="row from-group">
                <div class="col-md-4"><label for="">Grade</label></div>
                <div class="col-md-8"><input type="text" name="grade" value="{{$remarkScale->grade}}" class="form-control"></div>
            </div><br>
            <button class="btn btn-primary">Update</button>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>