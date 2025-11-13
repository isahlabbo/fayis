<div class="modal fade" id="addSubject" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">REGISTER NEW {{$sectionClass->name}} SUBJECT</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="{{route('section.class.subject.register',[$sectionClass->id])}}" method="post" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row">
            @foreach(App\Models\Subject::all() as $subject)
            <div class="col-md-6">
              <div class="row from-group">
                  <div class="col-md-10"><label for="">{{$subject->name}}</label></div>
                  <div class="col-md-2">
                  @if($sectionClass->hasThisSubject($subject))
                    <input type="checkbox" name="{{$subject->id}}" checked value="{{old('class')}}" class=""></div>
                  @else
                    <input type="checkbox" name="{{$subject->id}}" value="{{old('class')}}" class=""></div>                  
                  @endIf
              </div>
              <hr>
            </div>
            @endforeach
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