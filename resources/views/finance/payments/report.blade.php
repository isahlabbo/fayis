<x-app-layout>
    @section('title')
        Payment Report
    @endsection

    @section('content')
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h5 class="text-primary">Payment Reporting</h5>
                <p class="text-muted mb-0">Filter payments by section, class, term, and date range.</p>
            </div>
            <div class="btn-group">
                <a href="{{ route('finance.payments.report.pdf', request()->query()) }}" class="btn btn-sm btn-outline-primary">Download PDF</a>
                <a href="{{ route('finance.payments.report.csv', request()->query()) }}" class="btn btn-sm btn-outline-secondary">Download CSV</a>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <form method="get" class="row gy-3 gx-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label">Section</label>
                        <select name="section" class="form-control">
                            <option value="">All Sections</option>
                            @foreach($sections as $section)
                                <option value="{{ $section->id }}" {{ $selectedSection == $section->id ? 'selected' : '' }}>{{ $section->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
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
                    <div class="col-md-2">
                        <label class="form-label">Term</label>
                        <select name="term" class="form-control">
                            <option value="">All Terms</option>
                            @foreach($terms as $term)
                                <option value="{{ $term->id }}" {{ $selectedTerm == $term->id ? 'selected' : '' }}>{{ $term->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">From Date</label>
                        <input type="date" name="from_date" class="form-control" value="{{ $fromDate }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">To Date</label>
                        <input type="date" name="to_date" class="form-control" value="{{ $toDate }}">
                    </div>
                    <div class="col-md-12 text-end">
                        <button type="submit" class="btn btn-primary">Run Report</button>
                        <a href="{{ route('finance.payments.report') }}" class="btn btn-outline-secondary">Reset</a>
                    </div>
                </form>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card p-3">
                    <h6>Total Payments</h6>
                    <p class="h4 mb-0">{{ number_format($totals['count']) }}</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card p-3">
                    <h6>Total Collected</h6>
                    <p class="h4 mb-0">{{ number_format($totals['amount'], 2) }}</p>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-striped table-sm">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Section</th>
                        <th>Class</th>
                        <th>Student</th>
                        <th>Term</th>
                        <th>Fee</th>
                        <th>Amount</th>
                        <th>Mode</th>
                        <th>Recorded By</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payments as $payment)
                        <tr>
                            <td>{{ $payment->date }}</td>
                            <td>{{ $payment->sectionClassStudent->sectionClass->section->name ?? '-' }}</td>
                            <td>{{ $payment->sectionClassStudent->sectionClass->name ?? '-' }}</td>
                            <td>{{ $payment->sectionClassStudent->student->name ?? '-' }}</td>
                            <td>{{ $payment->term->name ?? '-' }}</td>
                            <td>{{ $payment->sectionClassFee->fee->name ?? '-' }}</td>
                            <td>{{ number_format($payment->amount, 2) }}</td>
                            <td>{{ $payment->mode }}</td>
                            <td>{{ $payment->user->name ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center">No payments found for the selected filters.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @endsection
</x-app-layout>
