@foreach(['Handwriting','Games','Sports',"Drawing And Painting",'Crafts'] as $psycho)
<div class="form-group">
  <div class="row">
       <div class="col-md-6"><b>{{$psycho}}</b></div>
       <div class="col-md-6">
           <select name="{{str_replace(' ','_',strtolower($psycho))}}" id="" class="form-control">
                @foreach([1,2,3,4,5] as $psychoScale)
                    <option value="{{$psychoScale}}">{{$psychoScale}}</option>
                @endforeach
           </select>
       </div>
  </div>
</div>
@endforeach