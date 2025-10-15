
<div class="modal fade" id="edit_{{$question->id}}" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">EDIT QUESTION </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form enctype="multipart/form-data"  action="{{route('dashboard.section.class.exam.subject.question.update',[$sectionClassSubject->sectionclass->id,$question->id])}}" method="post">
                    @csrf
                    
                    <div class="form-group row">
                        <div class="col-md-3"><label for="">Question</label></div>
                        <div class="col-md-9">
                            <textarea name="question" id="" cols="40" rows="4"  placeholder="Please write your question statement here">{{$question->question}}</textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-3"><label for="">Answer</label></div>
                        <div class="col-md-9">
                            <textarea name="answer" id="" cols="40" rows="4" placeholder="Please write your question answer here">{{$question->answer}}</textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-3"><label for="">Question Section</label></div>
                        <div class="col-md-9">
                            <select name="question_section_id" id="" class="form-control">
                                <option value="{{$question->examSubjectQuestionSection->id}}">{{$question->examSubjectQuestionSection->name}}</option>
                                @foreach($sectionClassSubject->currentQuestionSections() as $section)
                                    @if($question->examSubjectQuestionSection->id != $section->id)
                                    <option value="{{$section->id}}">{{$section->name}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-3"><label for="">Question Type</label></div>
                        <div class="col-md-9">
                            <select name="question_type_id" id="" class="form-control">
                                <option value="{{$question->questionType->id}}">{{$question->questionType->name}}</option>
                                @foreach(App\Models\QuestionType::all() as $type)
                                    @if($question->questionType->id != $type->id)
                                    <option value="{{$type->id}}">{{$type->name}}</option>
                                    @endif
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
                    
                    <button class="btn btn-secondary">UPDATE QUESTION</button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>