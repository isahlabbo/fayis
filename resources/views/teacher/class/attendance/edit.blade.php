<div class="modal fade" id="edit_{{$sectionClassStudent->currentStudentTerm()->sectionClassStudentTermAccessment->id}}" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Edit {{$sectionClassStudent->student->name}} Attendance</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <form action="{{route('teacher.class.attendance.update',[$sectionClassStudent->currentStudentTerm()->sectionClassStudentTermAccessment->id])}}" method="post">
        @csrf
        <div class="form-group">
            <label for="days_school_open">DAYS SCHOOL OPEN</label>
            <input type="number" value="{{$sectionClassStudent->currentStudentTerm()->sectionClassStudentTermAccessment->days_school_open}}" name="days_school_open" class="form-control">
        </div>
        <div class="form-group">
            <label for="days_present">DAYS PRESENT</label>
            <input type="number" value="{{$sectionClassStudent->currentStudentTerm()->sectionClassStudentTermAccessment->days_present}}" name="days_present" class="form-control">
        </div>

        <div class="form-group">
            <label for="days_absent">DAYS ABSENT</label>
            <input type="number" value="{{$sectionClassStudent->currentStudentTerm()->sectionClassStudentTermAccessment->days_absent}}" name="days_absent" class="form-control">
        </div>

        <div class="form-group">
            <button class="btn btn-secondary">Update</button>
        </div>    
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>