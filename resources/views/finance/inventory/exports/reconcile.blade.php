<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Inventory Reconciliation</title>
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
        <h1>Inventory Reconciliation Report</h1>
        <p>{{ now()->format('Y-m-d H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>SKU</th>
                <th>Name</th>
                <th>Type</th>
                <th>Quantity</th>
                <th>Unit Cost</th>
                <th>Notes</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transactions as $transaction)
                <tr>
                    <td>{{ optional($transaction->transaction_date)->format('Y-m-d') }}</td>
                    <td>{{ optional($transaction->item)->sku }}</td>
                    <td>{{ optional($transaction->item)->name }}</td>
                    <td>{{ ucfirst($transaction->type) }}</td>
                    <td>{{ $transaction->quantity }}</td>
                    <td>{{ number_format($transaction->unit_cost, 2) }}</td>
                    <td>{{ \Illuminate\Support\Str::limit($transaction->notes, 120) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>