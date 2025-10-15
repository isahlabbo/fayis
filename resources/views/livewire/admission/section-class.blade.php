<div class="form-group row">
    <div class="col-md-3"><label for="">Section</label></div>
    <div class="col-md-9">
    <select name="section" id="" class="form-control" wire:model="sections">
        <option>Select Section</option>
        @foreach($sections as $section)
            <option value="{{$section->id}}">{{$section->name}} Section</option>
        @endforeach
    </select>
    </div>
</div>
<div class="form-group row">
    <div class="col-md-3"><label for="">Class</label></div>
    <div class="col-md-9">
        <select name="class" id="" class="form-control">
            <option>Select Class</option>
        </select>
    </div>
</div>
