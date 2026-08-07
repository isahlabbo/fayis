<div>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-1">Inventory Reconciliation</h4>
            <p class="text-muted">Review transaction history and reconcile stock movements.</p>
        </div>
        <div>
            <a href="{{ route('finance.inventory.reconcile.pdf', ['from_date' => $from_date, 'to_date' => $to_date, 'searchTerm' => $searchTerm]) }}" class="btn btn-primary"><i class="fas fa-download"></i> Download PDF</a>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h5 class="card-title">Transactions</h5>
                    <p class="card-text display-4">{{ $transactions->count() }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h5 class="card-title">Total In</h5>
                    <p class="card-text display-4">{{ $transactions->where('type', 'in')->sum('quantity') }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h5 class="card-title">Total Out</h5>
                    <p class="card-text display-4">{{ $transactions->where('type', 'out')->sum('quantity') }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <form class="form-row align-items-end">
                <div class="form-group col-md-3">
                    <label for="from_date">From date</label>
                    <input wire:model="from_date" type="date" id="from_date" class="form-control">
                </div>
                <div class="form-group col-md-3">
                    <label for="to_date">To date</label>
                    <input wire:model="to_date" type="date" id="to_date" class="form-control">
                </div>
                <div class="form-group col-md-3">
                    <label for="searchTerm">Search item</label>
                    <input wire:model="searchTerm" id="searchTerm" class="form-control" placeholder="Item name or SKU">
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="thead-light">
                    <tr>
                        <th>Date</th>
                        <th>SKU</th>
                        <th>Item</th>
                        <th>Type</th>
                        <th>Qty</th>
                        <th>Cost</th>
                        <th>Notes</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $transaction)
                        <tr>
                            <td>{{ $transaction->transaction_date->format('Y-m-d') }}</td>
                            <td>{{ $transaction->item->sku }}</td>
                            <td>{{ $transaction->item->name }}</td>
                            <td>{{ ucfirst($transaction->type) }}</td>
                            <td>{{ $transaction->quantity }}</td>
                            <td>{{ number_format($transaction->unit_cost, 2) }}</td>
                            <td>{{ Str::limit($transaction->notes, 80) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">No reconciliation records found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>