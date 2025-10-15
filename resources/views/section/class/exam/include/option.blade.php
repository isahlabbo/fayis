
<div class="modal fade" id="option_{{$question->id}}" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form  action="{{route('dashboard.section.class.exam.question.newOption',[$question->examSubjectQuestionSection->sectionClassTermlyExam->id, $question->id])}}" method="post">
                    @csrf
                    <input type="hidden" value="{{$question->id}}" name="questionId">
                    <div class="form-group row">
                        <div class="col-md-3"><label for="">Option Value</label></div>
                        <div class="col-md-9">
                            <input type="text" name="value" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-3"><label for="">Option Name</label></div>
                        <div class="col-md-9">
                            <select name="name" id="" class="form-control">
                                
                                @foreach($question->availableOptions() as $name)
                                    <option value="{{$name}}">{{$name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <button class="btn btn-secondary">ADD</button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>