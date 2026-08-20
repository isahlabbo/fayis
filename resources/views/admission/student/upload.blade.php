@php($currentSession = $sectionClass->currentSession())
<div class="modal fade" id="upload" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Upload Registered Students to {{ $sectionClass->name }}{{ $currentSession ? ' — '.$currentSession->name : '' }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        @if($currentSession)
        <form action="{{route('section.class.upload',[$sectionClass->id])}}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="row from-group">
                <div class="col-md-4"><label for="">Choose File</label></div>
                <div class="col-md-8"><input type="file" name="template" id="" value="{{old('accessment')}}" class="form-control"></div>
            </div>
            <button class="btn btn-primary">Upload</button>
        </form>
        @else
          <div class="alert alert-warning mb-0" role="alert">
            <i class="fas fa-exclamation-triangle mr-1"></i>
            No active academic session is configured. Activate a session before uploading students.
          </div>
        @endif
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
