<div class="modal fade" id="makePayment" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title center" id="exampleModalLabel">BUY REGISTRATION FORM</h5>
      </div>
      <div class="modal-body">
        <form action="{{route('dashboard.payment.initiate')}}" method="post">
            @csrf
            <input type="hidden" name="amount" value="{{App\Models\Section::find(1)->currentSession()->form_fee}}">
            <button class="btn btn-primary"><i class="fas fa-credit-card"></i> Pay Now</button>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>