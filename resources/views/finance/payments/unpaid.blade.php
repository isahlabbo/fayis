<x-app-layout>
    @section('title')
        Unpaid Report
    @endsection

    @section('content')
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h5 class="text-primary">Unpaid Report</h5>
                <p class="text-muted mb-0">Search students with outstanding invoices by section, class, or student name.</p>
            </div>
            <div class="btn-group">
                <a href="{{ route('finance.payments.unpaid.pdf', request()->query()) }}" class="btn btn-sm btn-outline-primary">Download PDF</a>
                <a href="{{ route('finance.payments.unpaid.csv', request()->query()) }}" class="btn btn-sm btn-outline-secondary">Download CSV</a>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <form method="get" class="row gy-3 gx-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label">Section</label>
                        <select name="section" class="form-control">
                            <option value="">All Sections</option>
                            @foreach($sections as $section)
                                <option value="{{ $section->id }}" {{ $selectedSection == $section->id ? 'selected' : '' }}>{{ $section->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Class</label>
                        <select name="section_class" class="form-control">
                            <option value="">All Classes</option>
                            @foreach($classes as $class)
                                @if(!$selectedSection || $class->section_id == $selectedSection)
                                    <option value="{{ $class->id }}" {{ $selectedClass == $class->id ? 'selected' : '' }}>{{ $class->name }} ({{ $class->section->name }})</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Search Student</label>
                        <input type="text" name="search" class="form-control" value="{{ $selectedSearch }}" placeholder="Name or admission no">
                    </div>
                    <div class="col-md-12 text-end">
                        <button type="submit" class="btn btn-primary">Run Report</button>
                        <a href="{{ route('finance.payments.unpaid') }}" class="btn btn-outline-secondary">Reset</a>
                    </div>
                </form>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card p-3">
                    <h6>Students With Outstanding Balance</h6>
                    <p class="h4 mb-0">{{ number_format($totals['count']) }}</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card p-3">
                    <h6>Total Outstanding Amount</h6>
                    <p class="h4 mb-0">{{ number_format($totals['amount'], 2) }}</p>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-striped table-sm">
                <thead>
                    <tr>
                        <th>Student</th>
                        <th>Admission No</th>
                        <th>Class</th>
                        <th>Section</th>
                        <th>Amount Due</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($unpaidStudents as $student)
                        @foreach($student->sectionClassStudentTerms as $term)
                            @php $invoice = $term->invoice; @endphp
                            @if($invoice && $invoice->status !== 'paid')
                                <tr>
                                    <td>{{ $student->student->name ?? '-' }}</td>
                                    <td>{{ $student->student->admission_no ?? '-' }}</td>
                                    <td>{{ $student->sectionClass->name ?? '-' }}</td>
                                    <td>{{ $student->sectionClass->section->name ?? '-' }}</td>
                                    <td>{{ number_format($invoice->amount, 2) }}</td>
                                    <td>{{ $invoice->status ?? 'Unpaid' }}</td>
                                </tr>
                            @endif
                        @endforeach
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">No unpaid students found for the selected filters.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @endsection
</x-app-layout>
