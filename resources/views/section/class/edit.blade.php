<div class="modal fade" id="class_{{$sectionClass->id}}" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">{{$sectionClass->name}} Edit</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="{{route('section.class.update',[$sectionClass->id])}}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="row from-group">
                <div class="col-md-4"><label for="">Class Name</label></div>
                <div class="col-md-8"><input type="text" name="class" value="{{$sectionClass->name}}" class="form-control"></div>
            </div><br>
            <div class="row from-group">
                <div class="col-md-4"><label for="">Class Group</label></div>
                <div class="col-md-8">
                  <select name="class_group" id="" class="form-control">
                    <option value="{{$sectionClass->sectionClassGroup->id ?? ''}}">{{$sectionClass->sectionClassGroup->name ?? ''}}</option>
                    @foreach(App\Models\SectionClassGroup::all() as $sectionClassGroup)
                      <option value="{{$sectionClassGroup->id}}">{{$sectionClassGroup->name}}</option>
                    @endforeach
                  </select>
                </div>
            </div><br>
            <div class="row from-group">
                <div class="col-md-4"><label for="">Year Sequence</label></div>
                <div class="col-md-8">
                  <select name="year_sequence" id="" class="form-control">
                    <option value="{{$sectionClass->year_sequence}}">{{$sectionClass->year_sequence}}</option>
                    @foreach($sectionClass->section->yearSequences() as $yearSequence)
                      @if($sectionClass->year_sequence != $yearSequence)
                      <option value="{{$yearSequence}}">{{$yearSequence}}</option>
                      @endif
                    @endforeach
                  </select>
                </div>
            </div><br>
            <div class="row from-group">
                <div class="col-md-4"><label for="">Class Result Type</label></div>
                <div class="col-md-8">
                <select name="result_type" class="form-control">
                  <option value="{{$sectionClass->result_type_id}}}">{{$sectionClass->resultType->name ?? 'Result Type'}}</option>
                  @foreach(App\Models\ResultType::all() as $type)
                    @if($type->id != $sectionClass->remark_type_id)
                      @if($type->id != $sectionClass->result_type_id)
                        <option value="{{$type->id}}">{{$type->name}}</option>
                      @endif
                    @endif
                  @endforeach
                </select>
                </div>  
            </div><br>
            <div class="row from-group">
                <div class="col-md-4"><label for="">Class Pass Mark</label></div>
                <div class="col-md-8">
                <select name="pass_mark" class="form-control">
                  <option value="{{$sectionClass->pass_mark}}}">{{$sectionClass->pass_mark}}</option>
                  @for($i=1; $i<=100; $i++)
                    @if($i != $sectionClass->pass_mark)
                      <option value="{{$i}}">{{$i}} %</option>
                    @endif
                  @endfor
                </select>  
            </div>
            <button class="btn btn-primary">update</button>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>