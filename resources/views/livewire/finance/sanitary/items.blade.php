<div class="container-fluid py-3">
    <h3>Sanitary Material — Items</h3>
    <p class="text-muted">Manage sanitary supplies and their units. Receive quantities on the Stock screen.</p>
    @include('livewire.finance.sanitary.feedback')
    @if($this->canManage)
    <div class="card shadow-sm mb-3"><div class="card-body">
        <h5>{{ $itemId ? 'Edit item' : 'Add item' }}</h5>
        <form wire:submit.prevent="saveItem">
            <div class="form-row">
                <div class="form-group col-md-4"><label for="item-name">Item name</label><input id="item-name" wire:model.defer="name" class="form-control" maxlength="255" required></div>
                <div class="form-group col-md-3"><label for="item-sku">Code / SKU (optional)</label><input id="item-sku" wire:model.defer="sku" class="form-control" maxlength="100"></div>
                <div class="form-group col-md-3"><label for="item-unit">Unit</label><input id="item-unit" wire:model.defer="unit" class="form-control" placeholder="e.g. bottle, pack, piece" maxlength="50" required></div>
                <div class="form-group col-md-2"><label for="item-reorder">Low-stock level</label><input id="item-reorder" type="number" min="0" step="1" wire:model.defer="reorder_level" class="form-control" required></div>
            </div>
            <div class="form-group"><label for="item-notes">Notes (optional)</label><textarea id="item-notes" wire:model.defer="notes" class="form-control" rows="2" maxlength="1000"></textarea></div>
            <button type="submit" wire:loading.attr="disabled" class="btn btn-primary">{{ $itemId ? 'Save changes' : 'Add item' }}</button>
            <button type="button" wire:click="resetForm" class="btn btn-outline-secondary">{{ $itemId ? 'Cancel edit' : 'Clear' }}</button>
        </form>
    </div></div>
    @endif
    <div class="card shadow-sm"><div class="card-body border-bottom"><label for="items-search">Search items</label><input id="items-search" wire:model.debounce.300ms="search" class="form-control" placeholder="Item name or code"></div>
        <div class="table-responsive"><table class="table table-hover mb-0"><thead class="thead-light"><tr><th>Item</th><th>Code</th><th>Unit</th><th>Available</th><th>Low-stock level</th><th>Status</th>@if($this->canManage)<th>Actions</th>@endif</tr></thead><tbody>
        @forelse($records as $record)
            <tr wire:key="sanitary-item-{{ $record->id }}"><td>{{ $record->name }}@if($record->notes)<small class="d-block text-muted">{{ $record->notes }}</small>@endif</td><td>{{ $record->sku ?: '—' }}</td><td>{{ $record->unit }}</td><td>{{ number_format($record->available_quantity ?? 0) }}</td><td>{{ number_format($record->reorder_level) }}</td><td><span class="badge badge-{{ ($record->available_quantity ?? 0) <= $record->reorder_level ? 'warning' : 'success' }}">{{ ($record->available_quantity ?? 0) <= $record->reorder_level ? 'Low stock' : 'Available' }}</span></td>@if($this->canManage)<td class="text-nowrap">
                <button type="button" wire:click="editItem({{ $record->id }})" wire:loading.attr="disabled" class="btn btn-sm btn-outline-primary">Edit</button>
                <button type="button" wire:click="deleteItem({{ $record->id }})" onclick="confirm('Delete this sanitary item? Items with stock history cannot be deleted.') || event.stopImmediatePropagation()" wire:loading.attr="disabled" class="btn btn-sm btn-outline-danger">Delete</button>
            </td>@endif</tr>
        @empty<tr><td colspan="{{ $this->canManage ? 7 : 6 }}" class="text-center text-muted py-4">No sanitary items found.</td></tr>@endforelse
        </tbody></table></div><div class="card-footer">{{ $records->links() }}</div>
    </div>
</div>
