<div class="row">
@foreach(\App\Models\Section::all() as $section)
<div class="col-md-3 mb-4" style="border-radius: 10px !important;">
    <a href="{{route('patron.section.index',[$section->id])}}">
        <div class="card-body shadow text text-center" style="border-radius: 10px !important;">
            <h5 class="text text-primary center">{{$section->name}}</h5>
            <h5 class="text text-primary"> {{count($section->sectionClasses)}} Classes</h5>
        </div>
    </a>
</div>
@endforeach
</div>