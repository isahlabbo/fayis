
<div class="modal fade" id="upload_{{$sectionClassSubject->id}}" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><b>UPLOAD RESULT OF <i>{{$sectionClassSubject->subject->name}}</i> FOR {{$sectionClassSubject->sectionClass->name}} {{$sectionClassSubject->currentSession()->name}}  {{$sectionClassSubject->currentSessionTerm()->term->name}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form enctype="multipart/form-data" action="{{route('dashboard.teacher.scoresheet.uploadNow',[$sectionClassSubject->id])}}" method="post">
                    @csrf
                    <input type="hidden" value="{{$sectionClassSubject->id}}" name="sectionClassSubjectId">
                    <input type="hidden" value="{{$sectionClassSubject->currentSessionTerm()->term->id}}" name="term">
                    <div class="form-group row">
                        <div class="col-md-3"><label for="">Choose Score Sheet</label></div>
                        <div class="col-md-5">
                            <input type="file" name="score_sheet" id="" class="form-control">
                        </div>
                    </div>
                    
                    <button class="btn btn-secondary">UPLOAD RESULT</button>
                    
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>