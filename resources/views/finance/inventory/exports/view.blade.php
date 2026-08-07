<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Inventory Overview</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 20px; }
        .header p { margin: 0; color: #666; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; }
        th { background: #f3f3f3; text-align: left; }
        .summary { margin-top: 20px; }
        .summary p { margin: 4px 0; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Inventory Overview</h1>
        <p>{{ now()->format('Y-m-d H:i') }}</p>
    </div>

    <div class="summary">
        <p><strong>Total items:</strong> {{ $items->count() }}</p>
        <p><strong>Total quantity:</strong> {{ $items->sum('quantity') }}</p>
        <p><strong>Average unit cost:</strong> {{ number_format($items->avg('unit_cost') ?: 0, 2) }}</p>
        <p><strong>Total categories:</strong> {{ $categories->count() }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>SKU</th>
                <th>Name</th>
                <th>Category</th>
                <th>Quantity</th>
                <th>Unit Cost</th>
                <th>Value</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $item)
                <tr>
                    <td>{{ $item->sku }}</td>
                    <td>{{ $item->name }}</td>
                    <td>{{ optional($item->category)->name ?? 'Unassigned' }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ number_format($item->unit_cost, 2) }}</td>
                    <td>{{ number_format($item->quantity * $item->unit_cost, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>