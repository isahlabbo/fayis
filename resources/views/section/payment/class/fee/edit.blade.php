<div class="modal fade" id="fee_{{$sectionClassPayment->id}}" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">{{$sectionClassPayment->name}} Edit</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="{{route('dashboard.section.class.fee.update',[$sectionClass->id, $sectionClassPayment->id])}}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="row from-group">
                <div class="col-md-4"><label for="">Fee Name</label></div>
                <div class="col-md-8"><input type="text" name="name" value="{{$sectionClassPayment->name}}" class="form-control"></div>
            </div><br>
            <div class="row from-group">
                <div class="col-md-4"><label for="">Amount</label></div>
                <div class="col-md-8"><input type="number" name="amount"  value="{{$sectionClassPayment->amount}}" class="form-control"></div>
            </div><br>
            <div class="row from-group">
                <div class="col-md-4"><label for="">Applying Gender</label></div>
                <div class="col-md-8">
                <select name="gender" id="" class="form-control">
                    <option value="{{$sectionClassPayment->gender->id ?? 3}}">{{$sectionClassPayment->gender->name ?? 'Both'}}</option>
                    @foreach(App\Models\Gender::all() as $gender)
                        @if($sectionClassPayment->gender_id != $gender->id)
                           <option value="{{$gender->id}}">{{$gender->name}}</option>
                        @endif
                    @endforeach
                </select>
                </div>
            </div><br>
            <div class="row from-group">
                <div class="col-md-4"><label for="">Term</label></div>
                <div class="col-md-8">
                <select name="term" id="" class="form-control">
                    <option value="{{$sectionClassPayment->term_id}}">{{$sectionClassPayment->term->name}}</option>
                    @foreach($terms as $term)
                        @if($sectionClassPayment->term_id != $term->id)
                           <option value="{{$term->id}}">{{$term->name}}</option>
                        @endif
                    @endforeach
                </select>
                
                </div>
            </div>
            <button class="btn btn-primary">UPDATE FEE</button>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>