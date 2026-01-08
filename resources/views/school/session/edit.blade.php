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
        <form action="{{route('administration.session.update',[$academicSession->id])}}" method="post">
            @csrf
            <div class="form-group">
                <label for="start_at">Session Start At</label>
                <input type="date" name="start_at" id="start_at" class="form-control" value="{{$academicSession->start}}">
            </div>
            <div class="form-group">
                <label for="end_at">Session End At</label>
                <input type="date" name="end_at" id="end_at" class="form-control" value="{{$academicSession->end_at}}">
            </div>
            <div class="form-group">
                <label for="application_start">Application Start</label>
                <input type="date" name="application_start" id="application_start" class="form-control" value="{{$academicSession->application_start}}">
            </div>
            <div class="form-group">
                <label for="application_start">Application End</label>
                <input type="date" name="application_end" id="application_start" class="form-control" value="{{$academicSession->application_start}}">
            </div>
            <div class="form-group">
                <label for="interview_start">Interview Start</label>
                <input type="date" name="interview_start" id="interview_start" class="form-control" value="{{$academicSession->interview_start}}">
            </div>
            <div class="form-group">
                <label for="interview_end">Interview End</label>
                <input type="date" name="interview_end" id="interview_end" class="form-control" value="{{$academicSession->interview_start}}">
            </div>
            <div class="form-group">
                <label for="form_fee">Application Form Fee</label>
                <input type="number" name="form_fee" id="form_fee" class="form-control" value="{{$academicSession->interview_start}}">
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