@foreach(['Punctuality','Attendance','Reliability','Neatness','Politeness','Honesty','Relationship with Pupils','Self Control','Attentiveness','Perseverance'] as $trait)
<div class="form-group">
  <div class="row">
       <div class="col-md-6"><b>{{$trait}}</b></div>
       <div class="col-md-6">
           <select name="{{str_replace(' ','_',strtolower($trait))}}" id="" class="form-control">
                @foreach([1,2,3,4,5] as $scale)
                    <option value="{{$scale}}">{{$scale}}</option>
                @endforeach
           </select>
       </div>
  </div>
</div>
@endforeach