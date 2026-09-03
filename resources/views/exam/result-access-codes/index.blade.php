@extends('layouts.app')

@section('title', 'Result Access Codes')

@section('content')
<div class="container-fluid py-4">
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h4 class="mb-1">Result Access Codes</h4>
            <p class="text-muted mb-4">Choose a session, term, section and class, then search by student, guardian or access code.</p>

            <form id="access-code-filters" method="GET" action="{{ route('exam.result-access-codes.index') }}">
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
                <div class="form-group mb-0">
                    <label for="search">Student, guardian or access code</label>
                    <input class="form-control" id="search" name="search" value="{{ request('search') }}" placeholder="Student name, admission number, guardian phone/email or access code">
                </div>
            </form>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">Please check the selected filters and try again.</div>
    @endif

    @if($studentTerms)
        <div class="card shadow-sm">
            <div class="card-body">
                @if($missingCodeCount > 0)
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-muted">{{ $missingCodeCount }} matching {{ Illuminate\Support\Str::plural('student', $missingCodeCount) }} without an access code.</span>
                        <form method="POST" action="{{ route('exam.result-access-codes.generate') }}">
                            @csrf
                            <input type="hidden" name="session" value="{{ request('session') }}">
                            <input type="hidden" name="term" value="{{ request('term') }}">
                            <input type="hidden" name="section" value="{{ request('section') }}">
                            <input type="hidden" name="class" value="{{ request('class') }}">
                            <input type="hidden" name="search" value="{{ request('search') }}">
                            <button type="submit" class="btn btn-success" onclick="return confirm('Generate access codes for all matching students who do not have one?')">
                                <i class="fas fa-key mr-1"></i> Generate missing codes
                            </button>
                        </form>
                    </div>
                @endif
                <div class="table-responsive">
                    <table class="table table-bordered table-hover mb-0">
                        <thead class="thead-light">
                            <tr><th>#</th><th>Student</th><th>Admission No.</th><th>Class</th><th>Guardian</th><th>Guardian Phone</th><th>Access Code</th></tr>
                        </thead>
                        <tbody>
                            @forelse($studentTerms as $studentTerm)
                                @php($student = $studentTerm->sectionClassStudent->student)
                                <tr>
                                    <td>{{ $studentTerms->firstItem() + $loop->index }}</td>
                                    <td>{{ $student->name }}</td>
                                    <td>{{ $student->admission_no ?: '—' }}</td>
                                    <td>{{ $studentTerm->sectionClassStudent->sectionClass->name }}</td>
                                    <td>{{ optional($student->guardian)->name ?: '—' }}</td>
                                    <td>{{ optional($student->guardian)->phone ?: '—' }}</td>
                                    <td><strong class="text-success">{{ $studentTerm->access_code ?: 'Not generated' }}</strong></td>
                                </tr>
                            @empty
                                <tr><td colspan="7" class="text-center text-muted py-4">No students match these filters.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">{{ $studentTerms->links() }}</div>
            </div>
        </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
    (function () {
        var form = document.getElementById('access-code-filters');
        var section = document.getElementById('section');
        var classSelect = document.getElementById('class');
        var filters = ['session', 'term', 'section', 'class'].map(function (id) { return document.getElementById(id); });
        var classOptions = Array.prototype.slice.call(classSelect.querySelectorAll('option[data-section]'));
        var search = document.getElementById('search');
        var searchTimer;

        function filtersAreComplete() {
            return filters.every(function (filter) { return filter.value !== ''; });
        }

        function refresh() {
            if (filtersAreComplete()) form.submit();
        }

        function filterClasses() {
            classOptions.forEach(function (option) {
                option.hidden = section.value !== '' && option.dataset.section !== section.value;
                option.disabled = option.hidden;
            });
            var chosen = classSelect.options[classSelect.selectedIndex];
            if (chosen && chosen.disabled) classSelect.value = '';
        }

        filters.forEach(function (filter) {
            filter.addEventListener('change', function () {
                if (filter === section) filterClasses();
                refresh();
            });
        });
        search.addEventListener('input', function () {
            window.clearTimeout(searchTimer);
            searchTimer = window.setTimeout(refresh, 450);
        });
        filterClasses();
    }());
</script>
@endsection
