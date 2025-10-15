<div class="modal fade" id="addClass" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">REGISTER NEW {{$section->name}} CLASS</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="{{route('section.class.register',[$section->id])}}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="row from-group">
                <div class="col-md-4"><label for="">Class Name</label></div>
                <div class="col-md-8"><input type="text" name="class" placeholder="{{$section->name}} CLASS NAME" value="{{old('class')}}" class="form-control"></div>
            </div><br>
            <div class="row from-group">
                <div class="col-md-4"><label for="">Class Group</label></div>
                <div class="col-md-8">
                <select name="class_group" id="" class="form-control">
                  <option value="">Class Group</option>
                  @foreach(App\Models\SectionClassGroup::all() as $classGroup)
                    <option value="{{$classGroup->id}}">{{$classGroup->name}}</option>
                  @endforeach
                </select>
                </div>
            </div><br>
            <div class="row from-group">
                <div class="col-md-4"><label for="">Class Year Sequence</label></div>
                <div class="col-md-8">
                <select name="year_sequence" id="" class="form-control">
                  <option value="">Sequence Year</option>
                  @foreach($section->yearSequences() as $sequence)
                    <option value="{{$sequence}}">{{$sequence}}</option>
                  @endforeach
                </select>
                </div>
            </div><br>
            <div class="row from-group">
                <div class="col-md-4"><label for="">Class Result Type</label></div>
                <div class="col-md-8">
                <select name="result_type" class="form-control">
                  <option value="">Result Type</option>
                  @foreach(App\Models\ResultType::all() as $type)
                    <option value="{{$type->id}}">{{$type->name}}</option>
                  @endforeach
                </select>
                </div>  
            </div><br>
            <div class="row from-group">
                <div class="col-md-4"><label for="">Class Pass Mark</label></div>
                <div class="col-md-8">
                <select name="pass_mark" class="form-control">
                  <option value="">Class Pass Mark</option>
                  @for($i=1; $i<=100; $i++)
                    <option value="{{$i}}">{{$i}} %</option>
                  @endfor
                </select>  
            </div><br>
            <button class="btn btn-primary">Register</button>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>