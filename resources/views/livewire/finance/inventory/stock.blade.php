<div>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-1">Stock</h4>
            <p class="text-muted">Manage item stock top-ups and batch availability.</p>
        </div>
        <div>
            <a href="{{ route('finance.inventory.stock.pdf', ['from_date' => $from_date, 'to_date' => $to_date, 'category_id' => $category_id, 'searchTerm' => $searchTerm]) }}" class="btn btn-primary"><i class="fas fa-download"></i> Download PDF</a>
        </div>
    </div>
@if(Auth::user()->role == 'finance_officer')
    <div class="card mb-4">
        <div class="card-body">
            <form wire:submit.prevent="saveStock" class="form-row align-items-end">
                <div class="form-group col-md-3">
                    <label for="item_id">Item</label>
                    <select wire:model="item_id" id="item_id" class="form-control">
                        <option value="">Select item</option>
                        @foreach($items as $item)
                            <option value="{{ $item->id }}">{{ $item->name }} ({{ $item->sku }})</option>
                        @endforeach
                    </select>
                    @error('item_id') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="form-group col-md-2">
                    <label for="stock_quantity">Quantity</label>
                    <input wire:model="stock_quantity" id="stock_quantity" type="number" min="1" class="form-control">
                    @error('stock_quantity') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="form-group col-md-2">
                    <label for="stock_unit_cost">Unit cost</label>
                    <input wire:model="stock_unit_cost" id="stock_unit_cost" type="number" step="0.01" class="form-control">
                    @error('stock_unit_cost') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="form-group col-md-2"><label for="stock_selling_price">Unit selling price</label><input wire:model="stock_selling_price" id="stock_selling_price" type="number" step="0.01" class="form-control">@error('stock_selling_price') <span class="text-danger">{{ $message }}</span> @enderror</div>
                <div class="form-group col-md-2">
                    <label for="received_date">Received date</label>
                    <input wire:model="received_date" id="received_date" type="date" class="form-control">
                    @error('received_date') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="form-group col-md-2">
                    <label for="notes">Notes</label>
                    <input wire:model="notes" id="notes" type="text" class="form-control">
                    @error('notes') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="form-group col-md-1 text-right">
                    <button type="submit" class="btn btn-success btn-block"><i class="fas fa-save"></i> {{ $stockId ? 'Update' : 'Add' }}</button>@if($stockId)<button type="button" wire:click="resetStockForm" class="btn btn-light btn-block">Cancel</button>@endif
                </div>
            </form>
        </div>
    </div>
@endif
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h5 class="card-title">Total SKUs</h5>
                    <p class="card-text h3">{{ $items->count() }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h5 class="card-title">Total Batch Remaining</h5>
                    <p class="card-text h3">{{ $stocks->sum('remaining_quantity') }}</p>
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
                    <h5 class="card-title">Stock Value</h5>
                    <p class="card-text h3">{{ number_format($items->sum(fn($item) => $item->quantity * $item->unit_cost), 2) }}</p>
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
                        <th>Item</th>
                        <th>Category</th>
                        <th>Qty</th>
                        <th>Cost price</th><th>Selling price</th><th>Potential profit</th>
                        <th>Last Updated</th>
                        <th>Value</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $item)
                        <tr>
                            <td>{{ $item->sku }}</td>
                            <td>{{ $item->name }}</td>
                            <td>{{ optional($item->category)->name ?? 'Unassigned' }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>{{ number_format($item->unit_cost,2) }}</td><td>{{ number_format($item->selling_price,2) }}</td><td>{{ number_format(($item->selling_price-$item->unit_cost)*$item->quantity,2) }}</td>
                            <td>{{ $item->updated_at->format('Y-m-d') }}</td>
                            <td>{{ number_format($item->quantity * $item->unit_cost, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-4">No stock records found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @error('stock_delete')<div class="alert alert-danger mt-3">{{$message}}</div>@enderror
    <div class="card mt-4"><div class="card-header bg-white"><strong>Stock price batches (oldest sold first)</strong></div><div class="table-responsive"><table class="table table-hover mb-0"><thead class="thead-light"><tr><th>Received</th><th>Item</th><th>Original qty</th><th>Remaining</th><th>Purchase price</th><th>Selling price</th><th>Expected batch profit</th><th>Actions</th></tr></thead><tbody>@forelse($stocks as $batch)<tr><td>{{optional($batch->received_date)->format('Y-m-d')}}</td><td>{{$batch->item->name}}</td><td>{{$batch->quantity}}</td><td>{{$batch->remaining_quantity}}</td><td>{{number_format($batch->unit_cost,2)}}</td><td>{{number_format($batch->unit_selling_price,2)}}</td><td>{{number_format(($batch->unit_selling_price-$batch->unit_cost)*$batch->remaining_quantity,2)}}</td><td class="text-nowrap"><button wire:click="editStock({{$batch->id}})" class="btn btn-sm btn-outline-primary">Edit</button> <button wire:click="deleteStock({{$batch->id}})" onclick="confirm('Delete this unused stock batch?')||event.stopImmediatePropagation()" class="btn btn-sm btn-outline-danger" @if($batch->remaining_quantity!=$batch->quantity) disabled @endif>Delete</button></td></tr>@empty<tr><td colspan="8" class="text-center py-4">No stock batches found.</td></tr>@endforelse</tbody></table></div></div>
</div>
