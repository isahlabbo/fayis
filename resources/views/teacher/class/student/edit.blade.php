
<div class="modal fade" id="edit_{{$sectionClassStudent->student->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Edit {{$sectionClassStudent->student->name}}'s Details</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form method="POST" action="{{route('teacher.class.student.update',[$sectionClassStudent->student->id])}}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="form-group">
              <label for="guardian_name">Upload Child Picture</label>
              <input type="file" class="form-control" id="" name="picture" value="{{old('picture')}}" required> 
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
            </form> 
        </div>
      </div>
    </div>
  </div>
