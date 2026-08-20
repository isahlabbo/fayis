<div class="row">
@forelse(App\Models\SectionClassSubjectTeacher::where('teacher_id',Auth::user()->teacher->id)->get() as $subject)
    @if($subject->sectionClassSubject && $subject->sectionClassSubject->status == 'Active')
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
    @endif
@empty
    <div class="col-12">
        <div class="card border-0 shadow-sm"><div class="card-body text-center py-5">
            <i class="fas fa-book-open text-muted mb-3" style="font-size:2rem"></i>
            <h5>No subjects assigned</h5>
            <p class="text-muted mb-0">Your assigned subjects will appear here.</p>
        </div></div>
    </div>
@endforelse
</div>
