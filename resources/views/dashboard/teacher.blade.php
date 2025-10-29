<div class="row">
@foreach(App\Models\SectionClassSubjectTeacher::where('teacher_id',Auth::user()->teacher->id)->get() as $subject)
    <div class="col-md-4 mb-4">
        <a href="{{route('teacher.subject.index', $subject->id)}}" class="text-decoration-none">
            <div class="card-body shadow text-center rounded-3">
                <h5 class="text-primary">
                    <i class="fas fa-book"></i>{{$subject->sectionClassSubject->subject->name}}
                </h5>
                <h6 class="text-primary">
                    {{$subject->sectionClassSubject->sectionClass->name}}
                </h6>
            </div>
        </a>
    </div>        

@endforeach

</div>