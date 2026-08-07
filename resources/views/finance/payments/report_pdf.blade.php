<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h2 { margin: 0; }
        .filters { margin-bottom: 16px; }
        .filters span { display: inline-block; margin-right: 16px; }
        table { width: 100%; border-collapse: collapse; }
        table th, table td { border: 1px solid #ccc; padding: 6px 8px; }
        table th { background: #f4f4f4; }
        .totals { margin-top: 16px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Payment Report</h2>
        <p>{{ date('Y-m-d H:i') }}</p>
    </div>
    @if(!empty($filters))
        <div class="filters">
            @foreach($filters as $key => $value)
                <span><strong>{{ $key }}:</strong> {{ $value }}</span>
            @endforeach
        </div>
    @endif
    <table>
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
            @foreach($payments as $payment)
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
            @endforeach
        </tbody>
    </table>
</body>
</html>
