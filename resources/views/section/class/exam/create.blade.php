
<div class="modal fade" id="newExam" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form  action="{{route('dashboard.section.class.exam.register',[$sectionClass->id])}}" method="post">
                    @csrf
                    <input type="hidden" value="{{$sectionClass->id}}" name="sectionClassId">
                    <div class="form-group row">
                        <div class="col-md-3"><label for="">Session</label></div>
                        <div class="col-md-9">
                            <select name="session" id="" class="form-control">
                                <option value="{{$sectionClass->currentSession()->id}}">{{$sectionClass->currentSession()->name}}</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-3"><label for="">Term</label></div>
                        <div class="col-md-9">
                            <select name="term" id="" class="form-control">
                                <option value="">Term</option>
                                @foreach($sectionClass->currentSession()->academicSessionTerms as $academicSessionTerm)
                                    <option value="{{$academicSessionTerm->id}}">{{$academicSessionTerm->term->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <button class="btn btn-secondary">CREATE EXAM</button>
                    
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>