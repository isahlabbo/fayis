<div class="container-fluid py-3">
    <h3>Sanitary Material — Daily Usage</h3>
    <p class="text-muted">Record items consumed each day, who used them, and where they were used.</p>
    @include('livewire.finance.sanitary.feedback')
    @if($this->canManage)
    <div class="card shadow-sm mb-3"><div class="card-body">
        <h5>{{ $usageId ? 'Edit usage #'.$usageId : 'Record daily usage' }}</h5>
        <form wire:submit.prevent="saveUsage">
            <div class="form-row">
                <div class="form-group col-md-4"><label for="usage-item">Item</label><select id="usage-item" wire:model="sanitary_item_id" class="form-control" @if($usageId) disabled @endif required><option value="">Select item</option>@foreach($items as $item)<option value="{{ $item->id }}">{{ $item->name }} ({{ $item->unit }})</option>@endforeach</select></div>
                <div class="form-group col-md-4"><label for="usage-stock">Stock batch</label><select id="usage-stock" wire:model="sanitary_stock_id" class="form-control" @if($usageId) disabled @endif required><option value="">Select batch</option>@foreach($batches as $batch)<option value="{{ $batch->id }}">#{{ $batch->id }} · {{ $batch->received_date->format('d M Y') }} · {{ $batch->remaining_quantity }} remaining · {{ number_format($batch->unit_cost, 2) }} / unit</option>@endforeach</select>
                    @if($sanitary_item_id && $batches->isEmpty())<small class="text-warning">No stock available. <a href="{{ route('finance.sanitary.stock') }}">Receive stock</a> first.</small>@endif
                </div>
                <div class="form-group col-md-2"><label for="usage-quantity">Quantity used</label><input id="usage-quantity" type="number" min="1" step="1" wire:model.defer="quantity" class="form-control" required></div>
                <div class="form-group col-md-2"><label for="usage-date">Usage date</label><input id="usage-date" type="date" max="{{ now()->toDateString() }}" wire:model.defer="usage_date" class="form-control" required></div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6"><label for="usage-person">Used by / issued to</label><input id="usage-person" wire:model.defer="used_by" class="form-control" maxlength="255" required></div>
                <div class="form-group col-md-6"><label for="usage-location">Location / purpose</label><input id="usage-location" wire:model.defer="location" class="form-control" placeholder="e.g. Girls hostel cleaning" maxlength="255" required></div>
            </div>
            <div class="form-group"><label for="usage-notes">Notes (optional)</label><textarea id="usage-notes" wire:model.defer="notes" class="form-control" rows="2" maxlength="1000"></textarea></div>
            @if($usageId)<p class="text-muted small">To change the item or stock batch, delete this entry and record it again.</p>@endif
            <button type="submit" wire:loading.attr="disabled" class="btn btn-primary">{{ $usageId ? 'Save changes' : 'Record usage' }}</button>
            <button type="button" wire:click="resetForm" class="btn btn-outline-secondary">{{ $usageId ? 'Cancel edit' : 'Clear' }}</button>
        </form>
    </div></div>
    @endif
    <div class="card shadow-sm"><div class="card-body border-bottom">
        @include('livewire.finance.sanitary.filters')
        <span class="mr-4">Units used: <strong>{{ number_format($summary->quantity ?? 0) }}</strong></span><span>Usage value: <strong>{{ number_format($summary->value ?? 0, 2) }}</strong></span>
        <small class="d-block text-muted">Totals reflect the filters above. Select the same From and To date to review a single day.</small>
    </div><div class="table-responsive"><table class="table table-hover mb-0"><thead class="thead-light"><tr><th>Date</th><th>Item / batch</th><th>Quantity</th><th>Unit cost</th><th>Total value</th><th>Used by</th><th>Location / purpose</th><th>Recorded by</th>@if($this->canManage)<th>Actions</th>@endif</tr></thead><tbody>
    @forelse($records as $record)
        <tr wire:key="sanitary-usage-{{ $record->id }}"><td>{{ $record->usage_date->format('d M Y') }}</td><td>{{ $record->stock->item->name }}<small class="d-block text-muted">Batch #{{ $record->sanitary_stock_id }}</small></td><td>{{ number_format($record->quantity) }} {{ $record->stock->item->unit }}</td><td>{{ number_format($record->unit_cost, 2) }}</td><td>{{ number_format($record->quantity * $record->unit_cost, 2) }}</td><td>{{ $record->used_by }}</td><td>{{ $record->location }}@if($record->notes)<small class="d-block text-muted">{{ $record->notes }}</small>@endif</td><td>{{ optional($record->user)->name ?: '—' }}</td>@if($this->canManage)<td class="text-nowrap">
            <button type="button" wire:click="editUsage({{ $record->id }})" wire:loading.attr="disabled" class="btn btn-sm btn-outline-primary">Edit</button>
            <button type="button" wire:click="deleteUsage({{ $record->id }})" onclick="confirm('Delete this usage entry and restore its stock quantity?') || event.stopImmediatePropagation()" wire:loading.attr="disabled" class="btn btn-sm btn-outline-danger">Delete</button>
        </td>@endif</tr>
    @empty<tr><td colspan="{{ $this->canManage ? 9 : 8 }}" class="text-center text-muted py-4">No usage records match these filters.</td></tr>@endforelse
    </tbody></table></div><div class="card-footer">{{ $records->links() }}</div></div>
</div>
