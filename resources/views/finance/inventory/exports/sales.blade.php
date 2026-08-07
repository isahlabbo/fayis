<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Inventory Sales Report</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; }
        th { background: #f3f3f3; text-align: left; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Inventory Sales Report</h1>
        <p>{{ now()->format('Y-m-d H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Date</th>
                <th>Student</th>
                <th>Items</th>
                <th>Qty</th>
                <th>Total</th>
                <th>Evidence</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sales as $sale)
                <tr>
                    <td>{{ $sale->id }}</td>
                    <td>{{ optional($sale->usage_date)->format('Y-m-d') }}</td>
                    <td>{{ optional(optional($sale->student)->student)->name ?? 'N/A' }}</td>
                    <td>{{ $sale->saleItems->pluck('item.name')->filter()->implode(', ') }}</td>
                    <td>{{ $sale->saleItems->sum('quantity') }}</td>
                    <td>{{ number_format($sale->total_cost, 2) }}</td>
                    <td>{{ $sale->evidence ?? $sale->notes }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
