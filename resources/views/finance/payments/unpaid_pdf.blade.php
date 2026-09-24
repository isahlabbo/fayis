<html>
<head>
    <style>
        @page { size: A4 landscape; margin: 12mm; }
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h2 { margin: 0; }
        .filters { margin-bottom: 16px; }
        .filters span { display: inline-block; margin-right: 16px; }
        table { width: 100%; border-collapse: collapse; }
        table th, table td { border: 1px solid #ccc; padding: 6px 8px; }
        table th { background: #f4f4f4; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Unpaid Report</h2>
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
                <th>Student</th>
                <th>Admission No</th>
                <th>Class</th>
                <th>Section</th>
                <th>Session</th><th>Term</th><th>Fee</th><th>Fee Due</th><th>Paid</th><th>Balance</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($unpaidStudents as $student)
                @foreach($student->outstandingFees as $balance)
                        <tr>
                            <td>{{ $student->student->name ?? '-' }}</td>
                            <td>{{ $student->student->admission_no ?? '-' }}</td>
                            <td>{{ $student->sectionClass->name ?? '-' }}</td>
                            <td>{{ $student->sectionClass->section->name ?? '-' }}</td>
                            <td>{{ $student->academicSession->name ?? '-' }}</td>
                                    <td>{{ $balance->term->name ?? '-' }}</td>
                                    <td>{{ $balance->fee->name ?? '-' }}</td>
                                    <td>{{ number_format($balance->due, 2) }}</td>
                                    <td>{{ number_format($balance->paid, 2) }}</td>
                                    <td>{{ number_format($balance->balance, 2) }}</td>
                            <td>{{ $balance->status }}</td>
                        </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>
</body>
</html>
