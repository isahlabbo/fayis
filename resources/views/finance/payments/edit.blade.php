<div class="modal fade" id="edit_{{$payment->id}}" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Edit Payment</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="{{route('finance.payments.update',[$payment->id])}}" method="post">
            @csrf
            @method('PUT')
            <div class="from-group mb-2">
                <label for="">Student</label>
                <select name="student" class="form-control" id="">
                    <option value="{{$payment->sectionClassStudent->id}}">{{$payment->sectionClassStudent->student->name}}</option>
                    @foreach($sectionClass->sectionClassStudents->where('status', 'Active') as $classStudent)
                        <option value="{{$classStudent->student->id}}">{{$classStudent->student->name}}</option>
                    @endforeach
                </select>
            </div>

            <div class="from-group mb-2">
                <label for="">Description</label>
                <select name="class_fee" class="form-control" id="">
                    <option value="{{$payment->sectionClassFee->id}}">{{$sectionClassFee->fee->name}}</option>
                    @foreach($sectionClass->sectionClassFees as $sectionClassFee)
                        <option value="{{$sectionClassFee->id}}">{{$sectionClassFee->fee->name}}</option>
                    @endforeach
                </select>
            </div>

            <div class="from-group mb-2">
                <label for="">Amount</label>
                <input type="number" name="amount"  value="{{$payment->amount}}" class="form-control">
            </div>

            <div class="from-group mb-2">
                <label for="">Mode of Payment</label>
                <select name="mode" class="form-control" id="">
                    <option value="{{$payment->mode}}">{{$payment->mode}}</option>
                    <option value="Cash">Cash</option>
                    <option value="Bank Transfer">Bank Transfer</option>
                </select>
            </div>

            <div class="from-group mb-2">
                <label for="">Term</label>
                <select name="term" class="form-control" id="">
                    <option value="{{$payment->term->id}}">{{$payment->term->name}}</option>
                    @foreach(App\Models\Term::all() as $term)
                        <option value="{{$term->id}}">{{$term->name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="from-group mb-2">
                <label for="">Date</label>
                <input type="date" name="date"  value="{{$payment->date}}" class="form-control">
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