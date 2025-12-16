==<div class="modal fade" id="newSubject" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">{{$teacher->user->name}} Add Subject</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <form action="{{route('administration.teacher.subject.add',[$teacher->id])}}" method="post">
      @csrf
        <div class="form-group">
          <label for="">Class</label>
          <select name="class" id="" class="form-control">
            <option value="">Select Class</option>
            @foreach(\App\Models\SectionClass::all() as $class)
            <option value="{{$class->id}}">{{$class->name}}</option>
            @endforeach
          </select>
        </div>

        <div class="form-group">
          <label for="subject">Subject</label>
          <select name="subject" id="" class="form-control">
            <option value="">Subject</option>
          </select>
        </div>
        <button class="btn btn-primary">Add Subject</button>
      </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>