@extends('layouts.app')

@section('title', 'Student Results')

@section('content')
<div class="container-fluid py-4">
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h4 class="mb-1">Student Results</h4>
            <p class="text-muted mb-4">Choose a session, term, section and class to view report cards.</p>

            <form id="student-result-filters" method="GET" action="{{ route('exam.student-results.index') }}">
                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label for="session">Session</label>
                        <select class="form-control" id="session" name="session" required>
                            <option value="">Select session</option>
                            @foreach($sessions as $session)
                                <option value="{{ $session->id }}" {{ (string) request('session') === (string) $session->id ? 'selected' : '' }}>{{ $session->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="term">Term</label>
                        <select class="form-control" id="term" name="term" required>
                            <option value="">Select term</option>
                            @foreach($terms as $term)
                                <option value="{{ $term->id }}" {{ (string) request('term') === (string) $term->id ? 'selected' : '' }}>{{ $term->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="section">Section</label>
                        <select class="form-control" id="section" name="section" required>
                            <option value="">Select section</option>
                            @foreach($sections as $section)
                                <option value="{{ $section->id }}" {{ (string) request('section') === (string) $section->id ? 'selected' : '' }}>{{ $section->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="class">Class</label>
                        <select class="form-control" id="class" name="class" required>
                            <option value="">Select class</option>
                            @foreach($sections as $section)
                                @foreach($section->sectionClasses as $class)
                                    <option value="{{ $class->id }}" data-section="{{ $section->id }}" {{ (string) request('class') === (string) $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                                @endforeach
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label for="search">Student or guardian</label>
                        <input class="form-control" id="search" name="search" value="{{ request('search') }}" placeholder="Name, admission number, guardian phone or email">
                    </div>
                </div>
            </form>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">Please check the selected filters and try again.</div>
    @endif

    @if($students)
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover mb-0">
                        <thead class="thead-light">
                            <tr><th>#</th><th>Student</th><th>Admission No.</th><th>Guardian</th><th>Guardian Phone</th><th>Result</th><th>Action</th></tr>
                        </thead>
                        <tbody>
                            @forelse($students as $enrolment)
                                @php($studentTerm = $enrolment->sectionClassStudentTerms->first())
                                <tr>
                                    <td>{{ $students->firstItem() + $loop->index }}</td>
                                    <td>{{ $enrolment->student->name }}</td>
                                    <td>{{ $enrolment->student->admission_no ?: '—' }}</td>
                                    <td>{{ optional($enrolment->student->guardian)->name ?: '—' }}</td>
                                    <td>{{ optional($enrolment->student->guardian)->phone ?: '—' }}</td>
                                    <td>
                                        @if($studentTerm && $studentTerm->studentResults->isNotEmpty())
                                            <span class="badge badge-success">Available</span>
                                        @else
                                            <span class="badge badge-secondary">No result</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($studentTerm)
                                            <a class="btn btn-sm btn-outline-success" href="{{ route('exam.student-results.download', $studentTerm) }}">
                                                <i class="fas fa-file-pdf mr-1"></i> Download report card
                                            </a>
                                        @else
                                            <button class="btn btn-sm btn-outline-secondary" disabled>No term record</button>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="7" class="text-center text-muted py-4">No students match these filters.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">{{ $students->links() }}</div>
            </div>
        </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
    (function () {
        var form = document.getElementById('student-result-filters');
        var section = document.getElementById('section');
        var classSelect = document.getElementById('class');
        var search = document.getElementById('search');
        var filters = ['session', 'term', 'section', 'class'].map(function (id) {
            return document.getElementById(id);
        });
        var options = Array.prototype.slice.call(classSelect.querySelectorAll('option[data-section]'));
        var searchTimer;

        function filtersAreComplete() {
            return filters.every(function (filter) { return filter.value !== ''; });
        }

        function refreshResults() {
            if (filtersAreComplete()) form.submit();
        }

        function filterClasses() {
            var selected = section.value;
            options.forEach(function (option) {
                option.hidden = selected !== '' && option.dataset.section !== selected;
                option.disabled = option.hidden;
            });
            var chosen = classSelect.options[classSelect.selectedIndex];
            if (chosen && chosen.disabled) classSelect.value = '';
        }

        filters.forEach(function (filter) {
            filter.addEventListener('change', function () {
                if (filter === section) filterClasses();
                refreshResults();
            });
        });

        search.addEventListener('input', function () {
            window.clearTimeout(searchTimer);
            searchTimer = window.setTimeout(refreshResults, 450);
        });

        filterClasses();
    }());
</script>
@endsection
