@extends('layouts.app')

@section('title', 'Result Audit')

@section('styles')
<style>
    .audit-stat { border: 1px solid #e3e8ee; border-radius: 10px; padding: 16px; height: 100%; background: #fff; }
    .audit-stat small { color: #6c757d; display: block; text-transform: uppercase; }
    .audit-stat strong { display: block; font-size: 1.55rem; margin-top: 4px; }
    .score-track { background: #e9ecef; border-radius: 20px; height: 9px; min-width: 100px; overflow: hidden; }
    .score-fill { background: #198754; height: 100%; }
    .audit-meta th { width: 18%; background: #f8f9fa; }
</style>
@endsection

@section('content')
@php
    $enrolment = $studentTerm->sectionClassStudent;
    $student = $enrolment->student;
    $published = $studentTerm->sectionClassStudentTermResultPublish;
    $assessment = $studentTerm->sectionClassStudentTermAccessment;
    $subjects = $results->count();
    $obtained = $results->sum(function ($result) { return (float) $result->total; });
    $obtainable = $subjects * 100;
    $average = $subjects ? $obtained / $subjects : 0;
@endphp

<div class="container-fluid py-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
        <div><h4 class="mb-1">Result Audit</h4><p class="text-muted mb-0">A transparent breakdown of the values used on this report card.</p></div>
        <div class="mt-2">
            <a href="{{ url()->previous() }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left mr-1"></i> Back</a>
            <a href="{{ route('exam.student-results.download', $studentTerm) }}" class="btn btn-success"><i class="fas fa-file-pdf mr-1"></i> Download report card</a>
        </div>
    </div>

    <div class="card shadow-sm mb-4"><div class="card-body p-0"><div class="table-responsive">
        <table class="table table-bordered audit-meta mb-0">
            <tr><th>Student</th><td>{{ $student->name }}</td><th>Admission No.</th><td>{{ $student->admission_no ?: '—' }}</td></tr>
            <tr><th>Session</th><td>{{ $studentTerm->academicSessionTerm->academicSession->name }}</td><th>Term</th><td>{{ $studentTerm->academicSessionTerm->term->name }}</td></tr>
            <tr><th>Section</th><td>{{ $enrolment->sectionClass->section->name }}</td><th>Class</th><td>{{ $enrolment->sectionClass->name }}</td></tr>
            <tr><th>Guardian</th><td>{{ optional($student->guardian)->name ?: '—' }}</td><th>Guardian Phone</th><td>{{ optional($student->guardian)->phone ?: '—' }}</td></tr>
        </table>
    </div></div></div>

    <div class="row mb-4">
        <div class="col-md-3 mb-3"><div class="audit-stat"><small>Total subjects</small><strong>{{ $subjects }}</strong></div></div>
        <div class="col-md-3 mb-3"><div class="audit-stat"><small>Total obtainable</small><strong>{{ number_format($obtainable, 0) }}</strong></div></div>
        <div class="col-md-3 mb-3"><div class="audit-stat"><small>Marks obtained</small><strong>{{ number_format($obtained, 2) }}</strong><small>Published: {{ optional($published)->obtain_marks ?? 'Not available' }}</small></div></div>
        <div class="col-md-3 mb-3"><div class="audit-stat"><small>Average / Position</small><strong>{{ number_format($average, 2) }}%</strong><small>Position: {{ optional($published)->position ?: 'Not available' }}</small></div></div>
    </div>

    @if($published && abs((float) $published->obtain_marks - $obtained) > 0.01)
        <div class="alert alert-warning"><strong>Audit difference:</strong> displayed subject totals equal {{ number_format($obtained, 2) }}, while the published report record stores {{ number_format($published->obtain_marks, 2) }}.</div>
    @endif

    <div class="card shadow-sm mb-4"><div class="card-body">
        <h5>Subject score breakdown</h5>
        <div class="table-responsive"><table class="table table-bordered table-hover">
            <thead class="thead-light"><tr><th>Subject</th><th>1st CA</th><th>2nd CA</th><th>Assignment</th><th>Exam</th><th>Calculated</th><th>Stored total</th><th>Position</th><th>Grade</th><th>Score</th><th>Audit</th></tr></thead>
            <tbody>
            @forelse($results as $result)
                @php
                    $calculated = (float) $result->first_ca + (float) $result->second_ca + (float) ($result->assignment ?? 0) + (float) $result->exam;
                    $stored = (float) $result->total;
                @endphp
                <tr>
                    <td>{{ $result->subjectTeacherTermlyUpload->sectionClassSubjectTeacher->sectionClassSubject->name }}</td>
                    <td>{{ $result->first_ca }}</td><td>{{ $result->second_ca }}</td><td>{{ $result->assignment ?? 0 }}</td><td>{{ $result->exam }}</td>
                    <td>{{ number_format($calculated, 2) }}</td><td><strong>{{ number_format($stored, 2) }}</strong></td>
                    <td>{{ $result->subjectTeacherTermlyUpload->position($result->total) }}</td><td>{{ $result->grade }}</td>
                    <td><div class="score-track"><div class="score-fill" style="width: {{ min(100, max(0, $stored)) }}%"></div></div></td>
                    <td>@if(abs($calculated - $stored) <= 0.01)<span class="badge badge-success">Correct</span>@else<span class="badge badge-danger">Mismatch</span>@endif</td>
                </tr>
            @empty
                <tr><td colspan="11" class="text-center text-muted">No subject results found for this session and term.</td></tr>
            @endforelse
            </tbody>
        </table></div>
    </div></div>

    <div class="row mb-4">
        <div class="col-md-6"><div class="card shadow-sm h-100"><div class="card-body">
            <h5>Affective traits</h5><table class="table table-bordered mb-0"><thead><tr><th>Trait</th><th>Rating</th></tr></thead><tbody>
            @forelse(optional($assessment)->sectionClassStudentTermAccessmentAffectiveTraits ?? collect() as $trait)
                <tr><td>{{ optional($trait->getAffectiveTrait())->name }}</td><td>{{ $trait->value }}</td></tr>
            @empty<tr><td colspan="2" class="text-muted">No affective-trait assessment.</td></tr>@endforelse
            </tbody></table>
        </div></div></div>
        <div class="col-md-6"><div class="card shadow-sm h-100"><div class="card-body">
            <h5>Psychomotor ratings</h5><table class="table table-bordered mb-0"><thead><tr><th>Skill</th><th>Rating</th></tr></thead><tbody>
            @forelse(optional($assessment)->sectionClassStudentTermAccessmentPsychomotors ?? collect() as $skill)
                @if($skill->getPsychomotor())<tr><td>{{ $skill->getPsychomotor()->name }}</td><td>{{ $skill->value }}</td></tr>@endif
            @empty<tr><td colspan="2" class="text-muted">No psychomotor assessment.</td></tr>@endforelse
            </tbody></table>
        </div></div></div>
    </div>

    <div class="card shadow-sm"><div class="card-body">
        <h5>Attendance and remarks</h5>
        <table class="table table-bordered mb-0">
            <tr><th>Days open</th><td>{{ optional($assessment)->days_school_open ?? 0 }}</td><th>Present</th><td>{{ optional($assessment)->days_present ?? 0 }}</td><th>Absent</th><td>{{ optional($assessment)->days_absent ?? 0 }}</td></tr>
            <tr><th>Class master's remark</th><td colspan="5">{{ optional(optional($assessment)->teacherComment)->name ?: '—' }}</td></tr>
            <tr><th>Head of school's remark</th><td colspan="5">{{ optional(optional($assessment)->headTeacherComment)->name ?: '—' }}</td></tr>
        </table>
    </div></div>
</div>
@endsection
