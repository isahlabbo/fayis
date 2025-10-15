
<div class="modal fade" id="subject_{{$sectionClassSubject->id}}" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">ADD {{$sectionClassSubject->subject->name}} QUESTION FOR {{$sectionClassSubject->sectionClass->name}} </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form  action="{{route('dashboard.section.class.exam.subject.question.register',[$sectionClassSubject->sectionclass->id,$sectionClassSubject->currentExam()->id])}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" value="{{$sectionClassSubject->currentExam()->id}}" name="section_class_termly_exam_id">
                    <input type="hidden" value="{{$sectionClassSubject->id}}" name="section_class_subject_id">
                    <div class="form-group row">
                        <div class="col-md-3"><label for="">Question</label></div>
                        <div class="col-md-9">
                            <textarea name="question" id="" cols="40" rows="4" placeholder="Please write your question statement here"></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-3"><label for="">Answer</label></div>
                        <div class="col-md-9">
                            <textarea name="answer" id="" cols="40" rows="4" placeholder="Please write your question answer here"></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-3"><label for="">Question Section</label></div>
                        <div class="col-md-9">
                            <select name="question_section_id" id="" class="form-control">
                                <option value="">Question Section</option>
                                @foreach($sectionClassSubject->currentQuestionSections() as $section)
                                    <option value="{{$section->id}}">{{$section->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-3"><label for="">Question Type</label></div>
                        <div class="col-md-9">
                            <select name="question_type_id" id="" class="form-control">
                                <option value="">Question Type</option>
                                @foreach(App\Models\QuestionType::all() as $type)
                                    <option value="{{$type->id}}">{{$type->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-3"><label for="">Diagram</label></div>
                        <div class="col-md-9">
                            <input type="file" name="diagram" class="form-control">
                        </div>
                    </div>
                    
                    <button class="btn btn-secondary">ADD QUESTION</button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>