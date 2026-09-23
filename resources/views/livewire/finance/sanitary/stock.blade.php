<div class="container-fluid py-3">
    <h3>Sanitary Material — Stock</h3>
    <p class="text-muted">Receive and manage stock batches. Quantities use each item's unit of measure.</p>
    @include('livewire.finance.sanitary.feedback')
    @if($this->canManage && $items->isEmpty())<div class="alert alert-info">Start by <a href="{{ route('finance.sanitary.items') }}">adding a sanitary item</a>.</div>@endif
    @if($this->canManage)
    <div class="card shadow-sm mb-3"><div class="card-body">
        <h5>{{ $stockId ? 'Edit stock batch #'.$stockId : 'Receive stock' }}</h5>
        <form wire:submit.prevent="saveStock">
            <div class="form-row">
                <div class="form-group col-md-4"><label for="stock-item">Item</label><select id="stock-item" wire:model.defer="sanitary_item_id" class="form-control" @if($stockId) disabled @endif required><option value="">Select item</option>@foreach($items as $item)<option value="{{ $item->id }}">{{ $item->name }} ({{ $item->unit }})</option>@endforeach</select></div>
                <div class="form-group col-md-2"><label for="stock-quantity">Quantity received</label><input id="stock-quantity" type="number" min="1" step="1" wire:model.defer="quantity" class="form-control" required></div>
                <div class="form-group col-md-3"><label for="stock-cost">Unit cost</label><input id="stock-cost" type="number" min="0" step="0.01" wire:model.defer="unit_cost" class="form-control" required></div>
                <div class="form-group col-md-3"><label for="stock-date">Received date</label><input id="stock-date" type="date" max="{{ now()->toDateString() }}" wire:model.defer="received_date" class="form-control" required></div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6"><label for="stock-supplier">Supplier (optional)</label><input id="stock-supplier" wire:model.defer="supplier" class="form-control" maxlength="255"></div>
                <div class="form-group col-md-6"><label for="stock-reference">Invoice / reference (optional)</label><input id="stock-reference" wire:model.defer="reference" class="form-control" maxlength="100"></div>
            </div>
            <div class="form-group"><label for="stock-notes">Notes (optional)</label><textarea id="stock-notes" wire:model.defer="notes" class="form-control" rows="2" maxlength="1000"></textarea></div>
            @if($stockId)<p class="text-muted small">Quantity must include units already used. Correcting unit cost also updates the value of usage from this batch.</p>@endif
            <button type="submit" wire:loading.attr="disabled" class="btn btn-primary">{{ $stockId ? 'Save changes' : 'Receive stock' }}</button>
            <button type="button" wire:click="resetForm" class="btn btn-outline-secondary">{{ $stockId ? 'Cancel edit' : 'Clear' }}</button>
        </form>
    </div></div>
    @endif
    <div class="card shadow-sm"><div class="card-body border-bottom">
        @include('livewire.finance.sanitary.filters')
        <div class="d-flex flex-wrap"><span class="mr-4">Received units: <strong>{{ number_format($summary->received ?? 0) }}</strong></span><span class="mr-4">Available units: <strong>{{ number_format($summary->available ?? 0) }}</strong></span><span>Remaining stock value: <strong>{{ number_format($summary->value ?? 0, 2) }}</strong></span></div>
        <small class="text-muted">Totals reflect the filters above. Select an item to compare quantities in one unit.</small>
    </div><div class="table-responsive"><table class="table table-hover mb-0"><thead class="thead-light"><tr><th>Batch / date</th><th>Item</th><th>Received</th><th>Used</th><th>Remaining</th><th>Unit cost</th><th>Total cost</th><th>Supplier / reference</th>@if($this->canManage)<th>Actions</th>@endif</tr></thead><tbody>
    @forelse($records as $record)
        <tr wire:key="sanitary-stock-{{ $record->id }}"><td>#{{ $record->id }}<small class="d-block">{{ $record->received_date->format('d M Y') }}</small></td><td>{{ $record->item->name }}<small class="d-block text-muted">{{ $record->item->unit }}</small>@if($record->notes)<small class="d-block text-muted">{{ $record->notes }}</small>@endif</td><td>{{ number_format($record->quantity) }}</td><td>{{ number_format($record->quantity - $record->remaining_quantity) }}</td><td>{{ number_format($record->remaining_quantity) }}</td><td>{{ number_format($record->unit_cost, 2) }}</td><td>{{ number_format($record->quantity * $record->unit_cost, 2) }}</td><td>{{ $record->supplier ?: '—' }}<small class="d-block">{{ $record->reference }}</small></td>@if($this->canManage)<td class="text-nowrap">
            <button type="button" wire:click="editStock({{ $record->id }})" wire:loading.attr="disabled" class="btn btn-sm btn-outline-primary">Edit</button>
            <button type="button" wire:click="deleteStock({{ $record->id }})" onclick="confirm('Delete this unused stock batch?') || event.stopImmediatePropagation()" wire:loading.attr="disabled" class="btn btn-sm btn-outline-danger" @if($record->remaining_quantity != $record->quantity) disabled @endif>Delete</button>
        </td>@endif</tr>
    @empty<tr><td colspan="{{ $this->canManage ? 9 : 8 }}" class="text-center text-muted py-4">No stock batches match these filters.</td></tr>@endforelse
    </tbody></table></div><div class="card-footer">{{ $records->links() }}</div></div>
</div>
