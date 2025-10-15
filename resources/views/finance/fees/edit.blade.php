<div class="modal fade" id="edit_{{$sectionClassFeeItem->id}}" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Edit {{$sectionClassFeeItem->description}} of {{$sectionClassFee->sectionClass->name}}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="{{route('finance.fees.updateItem',[$sectionClassFeeItem->id])}}" method="post">
            @csrf
            <div class="from-group mb-2">
                <label for="">Description</label>
                <input type="text" name="description" value="{{$sectionClassFeeItem->description}}" class="form-control">
            </div>
            <div class="from-group mb-2">
                <label for="">Amount</label>
                <input type="number" name="amount"  value="{{$sectionClassFeeItem->amount}}" class="form-control">
            </div>
            <div class="from-group mb-2">
                <label for="">Gender</label>
                <select name="gender" class="form-control" id="">
                    <option value="{{$sectionClassFeeItem->gender->id}}">{{$sectionClassFeeItem->gender->name}}</option>
                    @foreach(App\Models\Gender::all() as $gender)
                        <option value="{{$gender->id}}">{{$gender->name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="from-group mb-2">
                <label for="">Term</label>
                <select name="term" class="form-control" id="">
                    <option value="{{$sectionClassFeeItem->term->id}}">{{$sectionClassFeeItem->term->name}}</option>
                    @foreach(App\Models\Term::all() as $term)
                        <option value="{{$term->id}}">{{$term->name}}</option>
                    @endforeach
                </select>
            </div>
            
            <button class="btn btn-primary">Save Changes</button>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>