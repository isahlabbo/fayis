

    <!-- Modal -->
    <div class="modal fade" id="testFee" tabindex="-1" aria-labelledby="studentConfigModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="studentConfigModalLabel">Student Configuration Form</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form action="{{route('dashboard.section.configuration.discount.test.fee')}}" method="post">
          @csrf   
          <div class="modal-body">
                  <div class="mb-3">
                      <label for="gender" class="form-label">Gender:</label>
                      <select id="gender" name="class" class="form-control">
                            <option value="">-- Select Class --</option>
                            @foreach(App\Models\SectionClass::all() as $class)
                            <option value="{{$class->id}}">{{$class->name}}</option>
                            @endforeach
                      </select>
                  </div>
                  <div class="mb-3">
                      <label for="gender" class="form-label">Gender:</label>
                      <select id="gender" name="gender" class="form-control">
                            <option value="">-- Select Gender --</option>
                            @foreach(App\Models\Gender::all() as $gender)
                            <option value="{{$gender->id}}">{{$gender->name}}</option>
                            @endforeach
                      </select>
                  </div>
                  <div class="mb-3">
                      <label for="student_type" class="form-label">Student Type:</label>
                      <select id="student_type" name="student_type" class="form-control">
                            <option value="">-- Select Student Type --</option>
                            @foreach(App\Models\StudentType::all() as $studentType)
                            <option value="{{$studentType->id}}">{{$studentType->name}}</option>
                            @endforeach
                      </select>
                  </div>
                  <div class="mb-3">
                      <label for="state" class="form-label">State:</label>
                      <select id="state" name="state" class="form-control">
                            <option value="">-- Select State --</option>
                            @foreach(App\Models\State::all() as $state)
                            <option value="{{$state->id}}">{{$state->name}}</option>
                            @endforeach
                      </select>
                  </div>
                  <div class="mb-3">
                      <label for="term" class="form-label">Term:</label>
                      <select id="term" name="term" class="form-control">
                            <option value="">-- Select Term --</option>
                            @foreach(App\Models\Term::all() as $term)
                            <option value="{{$term->id}}">{{$term->name}}</option>
                            @endforeach
                      </select>
                  </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Submit</button>
              </div>
          </form>
        </div>
      </div>
    </div>

   