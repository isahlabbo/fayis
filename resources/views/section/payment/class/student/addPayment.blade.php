<div class="modal fade" id="payment_{{$sectionClassStudent->id.$term->id}}" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">{{$sectionClassStudent->student->name}} ADD PAYMENT</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="{{route('dashboard.payment.class.student.pay',[$sectionClassStudent->id])}}" method="post" enctype="multipart/form-data">
            @csrf
            <input type="hidden" value="{{$term->id}}" name="term_id">
            <div class="row from-group">
                <div class="col-md-4"><label for="">Amount</label></div>
                <div class="col-md-8"><input type="text" name="amount"  value="{{$sectionClassStudent->feeAmount($term)-$sectionClassStudent->paidAmount($term)}}" class="form-control"></div>
            </div><br>
            <div class="row from-group">
                <div class="col-md-4"><label for="">Mode</label></div>
                <div class="col-md-8">
                <select name="mode" id="" class="form-control">
                    <option value="">Mode of Payment</option>
                    <option value="Cash">Cash</option>
                    <option value="Bank">Bank</option>
                </select>
                
                </div>
            </div>
            <button class="btn btn-primary">ADD PAYMENT</button>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

