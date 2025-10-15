<div class="modal fade" id="edit_{{$upload->id}}" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">EDIT UPLOAD</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="{{route('section.class.subject.update.upload',[$sectionClassSubject->sectionClass->id,$sectionClassSubject->id,$upload->academicSessionTerm->term->id,$upload->id])}}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="row from-group">
                <div class="col-md-4"><label for="">Term</label></div>
                <div class="col-md-8">
                    <select name="academic_session_term_id"  class="form-control">
                        <option value="{{$upload->academicSessionTerm->id}}">{{$upload->academicSessionTerm->term->name}}</option>
                        @foreach($upload->currentSession()->academicSessionTerms as $academicSessionTerm)
                            @if($upload->academicSessionTerm->id != $academicSessionTerm->id)
                            <option value="{{$academicSessionTerm->id}}">{{$academicSessionTerm->term->name}}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>
            <button class="btn btn-primary">Save</button>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>