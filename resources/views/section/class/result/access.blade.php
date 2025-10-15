<div class="modal fade" id="class_{{$sectionClass->id}}" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">UPLOAD STUDENT RESULT ACCESSMENT OF {{$sectionClass->name}}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="{{route('dashboard.section.class.result.accessment.upload',[$sectionClass->id])}}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="row from-group">
                <div class="col-md-4"><label for="">Choose File</label></div>
                <div class="col-md-8"><input type="file" name="accessment" id="" value="{{old('accessment')}}" class="form-control"></div>
            </div>
            <button class="btn btn-primary">Upload</button>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>