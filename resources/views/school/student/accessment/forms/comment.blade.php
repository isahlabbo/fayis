
<div class="form-group">
  <div class="row">
       <div class="col-md-6"><b>Form Teacher Remarks</b></div>
       <div class="col-md-6">
           <select name="comment" id="" class="form-control">
                @foreach($comments as $comment)
                    @if($sectionClassStudent->student->gender == $comment->gender || $comment->gender == 3)
                        <option value="{{$comment->id}}">{{$comment->name}}</option>
                    @endif
                @endforeach
           </select>
       </div>
  </div>
</div>
