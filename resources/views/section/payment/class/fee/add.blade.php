<div class="modal fade" id="addFee" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">{{$sectionClass->name}} ADD FEE ITEM</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="{{route('dashboard.section.class.fee.register',[$sectionClass->id])}}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="row from-group mt-2">
                <div class="col-md-4"><label for="">Fee Name</label></div>
                <div class="col-md-8"><input type="text" name="name" value="{{old('name')}}" class="form-control"></div>
            </div>
            <div class="row from-group mt-2">
                <div class="col-md-4"><label for="">Amount</label></div>
                <div class="col-md-8"><input type="number" name="amount"  value="{{old('amount')}}" class="form-control"></div>
            </div>
            <div class="row from-group mt-2">
                <div class="col-md-4"><label for="">Applying Gender</label></div>
                <div class="col-md-8">
                <select name="gender" id="" class="form-control">
                    <option value="">Select Gender</option>
                    @foreach([['name'=>'Male','id'=>1],['name'=>'Female','id'=>2],['name'=>'Both','id'=>3]] as $gender)
                       <option value="{{$gender['id']}}">{{$gender['name']}}</option>
                    @endforeach
                </select>
                </div>
            </div>
            <div class="row from-group mt-2">
                <div class="col-md-4"><label for="">Term</label></div>
                <div class="col-md-8">
                <select name="term" id="" class="form-control">
                    <option value="">Select Term</option>
                    @foreach($terms as $term)
                       <option value="{{$term->id}}">{{$term->name}}</option>
                    @endforeach
                </select>
                
                </div>
            </div>

            <div class="row from-group mt-2">
                <div class="col-md-4"><label for="">Student Type</label></div>
                <div class="col-md-8">
                    <select name="student_type" id="" class="form-control">
                        <option value="">Select Student Type</option>
                        @foreach(App\Models\StudentType::all() as $type)
                        <option value="{{$type->id}}">{{$type->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row from-group mt-2">
                <div class="col-md-4"><label for="">Assign this fee to all classes in the section</label></div>
                <div class="col-md-8">
                    <input type="checkbox" name="assign" id="">
                </div>
            </div>
            <button class="btn btn-primary">ADD FEE</button>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>