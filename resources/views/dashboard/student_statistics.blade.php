@php
    $currentSession = App\Models\AcademicSession::where('status', 'Active')->latest('id')->first()
        ?: App\Models\AcademicSession::latest('id')->first();
    $studentStatusCounts = App\Models\SectionClassStudent::query()
        ->when($currentSession, fn ($query) => $query->where('academic_session_id', $currentSession->id))
        ->whereIn('status', ['Active', 'Withdrawn'])
        ->select('status')
        ->selectRaw('COUNT(DISTINCT student_id) as total')
        ->groupBy('status')
        ->pluck('total', 'status');
@endphp
<div class="row mt-4">
    <div class="col-md-4 mb-3">
        @if(Auth::user()->hasAnyPermission('manage-students', 'manage-admissions'))<a href="{{ route('admission.student.index') }}" class="text-decoration-none">@endif
        <div class="card-body shadow text-center h-100">
            <h5 class="text-primary">Student profiles</h5>
            <h5 class="text-primary"><i class="fas fa-user-graduate"></i> {{ App\Models\Student::count() }}</h5>
        </div>
        @if(Auth::user()->hasAnyPermission('manage-students', 'manage-admissions'))</a>@endif
    </div>
    <div class="col-md-4 mb-3">
        <div class="card-body shadow text-center h-100">
            <h5 class="text-primary">Active students</h5>
            <h5 class="text-primary"><i class="fas fa-user-graduate"></i> {{ number_format($studentStatusCounts->get('Active', 0)) }}</h5>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card-body shadow text-center h-100">
            <h5 class="text-primary">Withdrawn students</h5>
            <h5 class="text-primary"><i class="fas fa-user-graduate"></i> {{ number_format($studentStatusCounts->get('Withdrawn', 0)) }}</h5>
        </div>
    </div>
</div>