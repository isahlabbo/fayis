
<div class="modal fade" id="addStudent" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add Student</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="{{route('dashboard.epayment.student.add',[$class->id])}}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="from-group mt-2">
                <label for="">Student Name</label>
                <input type="text" name="name" id="" class="form-input form-control">
            </div>

            <div class="from-group mt-2">
                <label for="">State</label>
                <select name="state" id="" class="form-input form-control">
                @foreach(App\Models\State::all() as $state)
                <option value="{{$state->id}}">{{$state->name}}</option>
                @endforeach
                </select>
            </div>
            <div class="from-group mt-2">
                <label for="">Lga</label>
                <select name="lga" id="" class="form-input form-control">
                    <option value="">Select LGA</option>
                </select>
            </div>
            <div class="from-group mt-2">
                <label for="">Studen Type</label>
                <select name="student_type" id="" class="form-input form-control">
                    <option value="">Student Type</option>
                        @foreach(App\Models\StudentType::all() as $studentType)
                        <option value="{{$studentType->id}}">{{$studentType->name}}</option>
                        @endforeach
                </select>
            </div>
            <div class="from-group mt-2">
                <label for="">Gender</label>
                <select name="gender" id="" class="form-input form-control">
                    <option value="">Gender</option>
                        @foreach(App\Models\Gender::all() as $gender)
                        <option value="{{$gender->id}}">{{$gender->name}}</option>
                        @endforeach
                </select>
            </div>
            <button class="btn btn-primary btn-sm mt-2">Add</button>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>