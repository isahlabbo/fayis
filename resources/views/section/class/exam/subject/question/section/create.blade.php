
<div class="modal fade" id="addSection" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">ADD {{$sectionClassSubject->subject->name}} QUESTION FOR {{$sectionClassSubject->sectionClass->name}} </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form  action="{{route('dashboard.section.class.exam.subject.question.section.register',[$exam->id, $sectionClassSubject->id])}}" method="post">
                    @csrf
                    <input type="hidden" value="{{$sectionClassSubject->currentExam()->id}}" name="section_class_termly_exam_id">
                    <input type="hidden" value="{{$sectionClassSubject->id}}" name="section_class_subject_id">
                    <div class="form-group row">
                        <div class="col-md-3"><label for="">Section Name</label></div>
                        <div class="col-md-9">
                            <input type="text" name="name" class="form-control" placeholder="Section A">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-3"><label for="">Section Instruction</label></div>
                        <div class="col-md-9">
                            <input type="text" name="instruction" class="form-control" placeholder="Section Instruction">
                        </div>
                    </div>
                    <button class="btn btn-secondary">ADD SECTION</button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>