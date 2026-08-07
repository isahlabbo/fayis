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
                <th>Amount Due</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($unpaidStudents as $student)
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
            @endforeach
        </tbody>
    </table>
</body>
</html>
