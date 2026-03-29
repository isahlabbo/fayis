<div class="modal fade" id="addQualification" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"> Upload your Qualification</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="{{route('profile.addQualification',Auth::user()->id)}}" method="post" enctype="multipart/form-data">
          @csrf
            <div class="form-group">
                <label for="">Qualification Name</label>
                <textarea name="type" id="" cols="30" rows="2" class="form-control" placeholder="Enter Your Qualification Title"></textarea>
            </div>
            <!-- staff ID -->
            <div class="form-group">
                <label for="">Upload Qualification</label>
                <input type="file" name="file" class="form-control"> 
            </div>

            <div class="form-group">
                <button class="btn btn-secondary">Save Qualification</button>
            </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
