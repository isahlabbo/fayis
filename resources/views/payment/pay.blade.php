<div class="modal fade" id="invoice_{{$invoice->id}}" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title center" id="exampleModalLabel">Let us know who is making the payment by completing the form below.</h5>
      </div>
      <div class="modal-body">
      <form action="{{route('payment.pay')}}" method="post">
            @csrf
            <input type="hidden" name="title" value="{{strtoupper($invoice->sectionClassStudentTerm->academicSessionTerm->term->name)}} PAYMENT OF {{$invoice->sectionClassStudentTerm->sectionClassStudent->student->name}} FROM {{$invoice->sectionClassStudentTerm->sectionClassStudent->sectionClass->name}}">
            <input type="hidden" name="id" value="{{$invoice->id}}">
            <input type="hidden" name="section_class_student_id" value="{{$sectionClassStudent->id}}">
            <input type="hidden" name="amount" value="{{$invoice->totalFee()}}">
            <input type="hidden" name="developer" value="{{$invoice->otherCharges()}}">
            
              <div class="form-group">
                <label for="email">You can also pay for the following terms</label>
                <div class="row">
                  <div class="col-md-4">
                    First Term<input type="checkbox" name="first" class="form-control" value="1">
                  </div>
                  <div class="col-md-4">
                    Second Term<input type="checkbox" name="second" class="form-control" value="2">
                  </div>
                  <div class="col-md-4">
                    Third Term<input type="checkbox" name="third" class="form-control" value="3">
                  </div>
                </div>
              </div>
                
            
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" class="form-control">
            </div>
            
            <input type="hidden" name="amount" value="{{$invoice->totalFee()}}">
            <button class="btn btn-info"><i class="fas fa-wallet"></i> Make Payment Now</button>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>