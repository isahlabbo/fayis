<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Inventory Categories</title>
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
        <h1>Inventory Categories</h1>
        <p>{{ now()->format('Y-m-d H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Category</th>
                <th>Description</th>
                <th>Item Count</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            @foreach($categories as $category)
                <tr>
                    <td>{{ $category->name }}</td>
                    <td>{{ \Illuminate\Support\Str::limit($category->description, 100) }}</td>
                    <td>{{ $category->items()->count() }}</td>
                    <td>{{ optional($category->created_at)->format('Y-m-d') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>