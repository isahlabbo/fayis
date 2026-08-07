<div>
    <div class="mb-4">
        <h5 class="text-primary">Payment Summary for {{ $section->name }}</h5>
        <p>Showing: {{ $feeTypeLabel }}</p>
    </div>

    @foreach($summaryData as $classSummary)
        <div class="card mb-4 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        <h6>{{ $classSummary['name'] }}</h6>
                        <p class="text-muted mb-0">Active students: {{ $classSummary['active_students'] }}</p>
                    </div>
                    <div>
                        @if($feeType !== 'all')
                            <a href="{{ route('finance.payments.index', ['sectionClassId' => $classSummary['id'], 'type' => $feeType]) }}" class="btn btn-sm btn-primary">Quick Add Payment</a>
                        @else
                            <a href="{{ route('finance.payments.index', ['sectionClassId' => $classSummary['id']]) }}" class="btn btn-sm btn-primary">View Payments</a>
                        @endif
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-sm table-bordered">
                        <thead>
                            <tr>
                                <th>Term</th>
                                <th>Expected</th>
                                <th>Collected</th>
                                <th>Pending</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($classSummary['terms'] as $termData)
                                <tr>
                                    <td>{{ $termData['term'] }}</td>
                                    <td>{{ number_format($termData['expected'], 2) }}</td>
                                    <td>{{ number_format($termData['collected'], 2) }}</td>
                                    <td>{{ number_format($termData['pending'], 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if(!empty($classSummary['school_breakdown']))
                    <div class="mt-3">
                        <h6>School Fees Breakdown</h6>
                        @foreach($classSummary['school_breakdown'] as $termName => $items)
                            <div class="mb-2">
                                <strong>{{ $termName }}</strong>
                                <ul class="mb-0">
                                    @foreach($items as $item)
                                        <li>{{ $item['description'] }}: {{ number_format($item['amount'], 2) }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    @endforeach
</div>
