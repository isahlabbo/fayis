
<div class="modal fade" id="staff_{{$staffChildrenDiscount->id}}" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
      <h5 class="modal-title" id="exampleModalLabel">{{$section->name}} Staff Children School Fee Discounts</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <p>Note you about to add the discount to all the staff children from {{$section->name}} section. Additionally, any
       amount specify here will be deduction from the actual invoice of the students.</p>
        <form action="{{route('dashboard.section.configuration.discount.staffchild')}}" method="post" enctype="multipart/form-data">
            @csrf
            
            <div class="form-group">
            <label for="">{{$staffChildrenDiscount->term->name}}</label>
            <input type="number" name="{{$staffChildrenDiscount->id}}" value="{{$staffChildrenDiscount->amount}}" id="" class="form-control">
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