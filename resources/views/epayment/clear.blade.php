
<div class="modal fade" id="clear_{{$sectionClassStudentTerm->invoice->id}}" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Invoice {{$sectionClassStudentTerm->invoice->number()}}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="{{route('dashboard.epayment.clear',[$sectionClassStudentTerm->sectionClassStudent->id])}}" method="post" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="charges" value="{{$sectionClassStudentTerm->invoice->charges()}}">
            <input type="hidden" name="invoice" value="{{$sectionClassStudentTerm->invoice->id}}">
            <div class="from-group mt-2">
                <label for="">Cleared By</label>
                <select name="rank" id="" class="form-input form-control">
                    <option value="">Cleared By</option>
                    @foreach(App\Models\Rank::all() as $rank)
                    <option value="{{$rank->id}}">{{$rank->name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="from-group mt-2">
                <label for="">Click this to pay all generate</label>
                <input type="checkbox" name="all" id="">
            </div>

            <button class="btn btn-primary btn-sm mt-2">Make Payment</button>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>