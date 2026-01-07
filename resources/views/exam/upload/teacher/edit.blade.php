<div class="modal fade" id="edit_{{$upload->id}}" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Edit {{$upload->sectionClassSubjectTeacher->sectionClassSubject->sectionClass->name}} - {{$upload->sectionClassSubjectTeacher->sectionClassSubject->subject->name}} Result</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <form action="{{route('exam.upload.teacher.update',[$upload->id])}}" method="post">
      @csrf
      
      <div class="form-group">
            <label for="">Return Result to</label>
            <select name="status" id="" class="form-control">
              <option value="0" {{ $upload->status == 0 ? 'selected' : '' }}>Subject Teacher</option>
              <option value="1" {{ $upload->status == 1 ? 'selected' : '' }}>Class Master</option>
              <option value="2" {{ $upload->status == 2 ? 'selected' : '' }}>Exam Office</option>
            </select>
      </div>
      <div class="form-group">
        <button type="submit" class="btn btn-primary">Update</button>
      </div>
       
  </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>