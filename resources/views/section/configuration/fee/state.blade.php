
<div class="modal fade" id="update_{{$section->id.$state->id}}" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
      <h5 class="modal-title" id="exampleModalLabel">{{$state->name}} School Fee Discounts</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <p>Note you about to add the discount to all the students from {{$state->name}} state. Additionally, any
       amount specify here will be deduction from the actual invoice of the student.</p>
        <form action="{{route('dashboard.section.configuration.discount.state')}}" method="post" enctype="multipart/form-data">
            @csrf
            
            @foreach($state->neighboringStateDiscounts->where('section_id', $section->id) as $stateDiscount)
            <div class="form-group">
            <label for="">{{$stateDiscount->term->name}}</label>
            <input type="number" name="{{$stateDiscount->id}}" value="{{$stateDiscount->amount}}" id="" class="form-control">
            </div>
            @endforeach
           
            <button class="btn btn-primary">Update</button>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>