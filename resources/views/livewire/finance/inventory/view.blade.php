<div>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-1">Items</h4>
            <p class="text-muted">Manage inventory items and their master details.</p>
        </div>
        <div>
            <a href="{{ route('finance.inventory.view.pdf', ['from_date' => $from_date, 'to_date' => $to_date, 'category_id' => $category_id, 'searchTerm' => $searchTerm]) }}" class="btn btn-primary"><i class="fas fa-download"></i> Download PDF</a>
        </div>
    </div>
    @if(in_array(Auth::user()->role, ['finance_officer','patron'], true) || Auth::user()->hasPermission('manage-inventory'))
    <div class="mb-4">
        @livewire('finance.inventory.crud')
    </div>
    @endif
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h5 class="card-title">Total Items</h5>
                    <p class="card-text h3">{{ $items->count() }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h5 class="card-title">Total Quantity</h5>
                    <p class="card-text h3">{{ $items->sum('quantity') }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h5 class="card-title">Average Cost</h5>
                    <p class="card-text h3">{{ number_format($items->avg('unit_cost') ?? 0, 2) }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h5 class="card-title">Categories</h5>
                    <p class="card-text h3">{{ $categories->count() }}</p>
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
                    <label for="category_id">Category</label>
                    <select wire:model="category_id" id="category_id" class="form-control">
                        <option value="">All categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-3">
                    <label for="searchTerm">Search item</label>
                    <input wire:model="searchTerm" id="searchTerm" class="form-control" placeholder="Name or SKU">
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="thead-light">
                    <tr>
                        <th>SKU</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Quantity</th>
                        <th>Unit Cost</th>
                        <th>Value</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $item)
                        <tr>
                            <td>{{ $item->sku }}</td>
                            <td>{{ $item->name }}</td>
                            <td>{{ optional($item->category)->name ?? 'Unassigned' }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>{{ number_format($item->unit_cost, 2) }}</td>
                            <td>{{ number_format($item->quantity * $item->unit_cost, 2) }}</td>
                            <td>
                                @if(in_array(Auth::user()->role, ['finance_officer','patron'], true) || Auth::user()->hasPermission('manage-inventory'))
                                    <button wire:click="$emit('editInventoryItem', {{ $item->id }})" class="btn btn-sm btn-outline-secondary"><i class="fas fa-pen"></i></button>
                                    <button wire:click="deleteItem({{ $item->id }})" type="button" onclick="confirm('Delete this item?') || event.stopImmediatePropagation()" class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">No inventory items found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
