<div class="modal fade" id="edit_{{$studentResult->id}}" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"> Edit Result</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <form action="{{route('exam.upload.result.update',[$studentResult->id])}}" method="post">
      @csrf
      @method('PUT')
        <div class="form-group row">
            <div class="col-md-3"><label for="">First CA</label></div>
            <div class="col-md-9">
                <input type="text" class="form-control" name="first_ca" value="{{$studentResult->first_ca}}" placeholder="Enter Teacher's Name">
            </div>
        </div>
        <div class="form-group row">
            <div class="col-md-3"><label for="">Second CA</label></div>
            <div class="col-md-9">
                <input type="text" class="form-control" name="second_ca" value="{{$studentResult->second_ca}}" placeholder="Enter Teacher's Name">
            </div>
        </div>
        <div class="form-group row">
            <div class="col-md-3"><label for="">Exam</label></div>
            <div class="col-md-9">
                <input type="text" class="form-control" name="exam" value="{{$studentResult->exam}}" placeholder="Enter Teacher's Name">
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