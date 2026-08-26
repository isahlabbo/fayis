<div>
    @if(Auth::user()->role === 'finance_officer' || Auth::user()->hasPermission('manage-sales'))
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-0">Sales</h5>
                <p class="mb-0 text-muted">Manage item sales and print receipts.</p>
            </div>
        </div>
        <div class="card-body">
            <form wire:submit.prevent="saveSale">
                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label for="section_id">Section</label>
                        <select wire:model="section_id" id="section_id" class="form-control">
                            <option value="">Select section</option>
                            @foreach($sections as $section)
                                <option value="{{ $section->id }}">{{ $section->name }}</option>
                            @endforeach
                        </select>
                        @error('section_id') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group col-md-3">
                        <label for="class_id">Class</label>
                        <select wire:model="class_id" id="class_id" class="form-control">
                            <option value="">Select class</option>
                            @foreach($classes as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                        @error('class_id') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group col-md-3">
                        <label for="student_id">Student</label>
                        <select wire:model="student_id" id="student_id" class="form-control">
                            <option value="">Select student</option>
                            @foreach($students as $student)
                                <option value="{{ $student['id'] }}">{{ $student['name'] }}</option>
                            @endforeach
                        </select>
                        @error('student_id') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group col-md-3">
                        <label for="selectedItems">Items</label>
                        <select wire:model="selectedItems" id="selectedItems" class="form-control" multiple size="6">
                            @foreach($items as $item)
                                <option value="{{ $item->id }}">{{ $item->name }} ({{ $item->sku }}) - {{ $item->quantity }} available</option>
                            @endforeach
                        </select>
                        @error('selectedItems') <span class="text-danger">{{ $message }}</span> @enderror
                        @error('selectedItems.*') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>

                @if(count($selectedItemModels) > 0)
                    <div class="mt-4">
                        <h6>Selected items</h6>
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm">
                                <thead>
                                    <tr>
                                        <th>Item</th>
                                        <th class="text-right">Available</th>
                                        <th class="text-right">Quantity</th>
                                        <th class="text-right">FIFO average price</th>
                                        <th class="text-right">Line total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($selectedItemModels as $item)
                                        <tr>
                                            <td>{{ $item->name }} ({{ $item->sku }})</td>
                                            <td class="text-right">{{ $item->quantity }}</td>
                                            <td class="text-right" style="width: 120px;">
                                                <input wire:model.lazy="itemQuantities.{{ $item->id }}" type="number" min="1" class="form-control form-control-sm text-right" />
                                                @error('itemQuantities.' . $item->id) <span class="text-danger small">{{ $message }}</span> @enderror
                                            </td>
                                            <td class="text-right" style="width: 140px;">
                                                <input wire:model="itemUnitCosts.{{ $item->id }}" type="number" step="0.01" class="form-control form-control-sm text-right" readonly />
                                                @error('itemUnitCosts.' . $item->id) <span class="text-danger small">{{ $message }}</span> @enderror
                                            </td>
                                            <td class="text-right">
                                                {{ number_format((($itemQuantities[$item->id] ?? 0) * ($itemUnitCosts[$item->id] ?? 0)), 2) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

                <div class="form-row mt-3">
                    <div class="form-group col-md-3">
                        <label for="usage_date">Sale date</label>
                        <input wire:model="usage_date" id="usage_date" class="form-control" type="date">
                        @error('usage_date') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group col-md-3">
                        <label for="total_cost">Total cost</label>
                        <input type="text" id="total_cost" class="form-control" value="{{ number_format($totalSaleAmount, 2) }}" readonly>
                    </div>
                    <div class="form-group col-md-3"><label for="payment_method">Payment method</label><select wire:model="payment_method" id="payment_method" class="form-control"><option>Cash</option><option>Transfer</option><option>POS</option><option>Cheque</option></select>@error('payment_method') <span class="text-danger">{{ $message }}</span> @enderror</div>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-success"><i class="fas fa-check"></i> {{ $saleId ? 'Update Sale' : 'Record Sale' }}</button>@if($saleId)<button type="button" wire:click="cancelEdit" class="btn btn-light ml-2">Cancel edit</button>@endif
                </div>
            </form>
        </div>
    </div>
@endif
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-0">Sales report</h5>
                <p class="mb-0 text-muted">Search past sales and download a report.</p>
            </div>
            <a href="{{ route('finance.inventory.sales.pdf', ['from_date' => $from_date, 'to_date' => $to_date, 'searchTerm' => $searchTerm]) }}" class="btn btn-outline-secondary"><i class="fas fa-download"></i> Download PDF</a>
        </div>
        <div class="card-body">
            <form class="form-row align-items-end mb-4">
                <div class="form-group col-md-3">
                    <label for="from_date">From date</label>
                    <input wire:model="from_date" id="from_date" type="date" class="form-control">
                </div>
                <div class="form-group col-md-3">
                    <label for="to_date">To date</label>
                    <input wire:model="to_date" id="to_date" type="date" class="form-control">
                </div>
                <div class="form-group col-md-4">
                    <label for="searchTerm">Search</label>
                    <input wire:model="searchTerm" id="searchTerm" class="form-control" placeholder="Student, item, payment method">
                </div>
            </form>

            <div class="row text-center mb-4">
                <div class="col-md-3 mb-2">
                    <div class="card shadow-sm border-0">
                        <div class="card-body py-3">
                            <h6 class="text-muted">Sales count</h6>
                            <div class="h3 mb-0">{{ $totalSalesCount }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="card shadow-sm border-0">
                        <div class="card-body py-3">
                            <h6 class="text-muted">Total revenue</h6>
                            <div class="h3 mb-0">{{ number_format($totalRevenue, 2) }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="card shadow-sm border-0">
                        <div class="card-body py-3">
                            <h6 class="text-muted">Items sold</h6>
                            <div class="h3 mb-0">{{ $totalItemsSold }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-2"><div class="card shadow-sm border-0"><div class="card-body py-3"><h6 class="text-muted">Gross profit</h6><div class="h3 mb-0">{{ number_format($totalProfit,2) }}</div></div></div></div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-hover mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th>ID</th>
                            <th>Date</th>
                            <th>Student</th>
                            <th>Items</th>
                            <th class="text-right">Qty</th>
                            <th class="text-right">Total</th>
                            <th class="text-right">Profit</th>
                            <th>Payment method</th>
                            <th class="text-right">Receipt</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sales as $sale)
                            <tr>
                                <td>{{ $sale->id }}</td>
                                <td>{{ optional($sale->usage_date)->format('Y-m-d') }}</td>
                                <td>{{ optional(optional($sale->student)->student)->name ?? 'N/A' }}</td>
                                <td>{{ $sale->saleItems->pluck('item.name')->filter()->implode(', ') }}</td>
                                <td class="text-right">{{ $sale->saleItems->sum('quantity') }}</td>
                                <td class="text-right">{{ number_format($sale->total_cost, 2) }}</td>
                                <td class="text-right text-success">{{ number_format($sale->saleItems->sum('profit'), 2) }}</td>
                                <td>{{ $sale->payment_method }}</td>
                                <td class="text-right">
                                    @if(Auth::user()->role === 'finance_officer' || Auth::user()->hasPermission('manage-sales'))
                                    <a href="{{ route('finance.inventory.sales.receipt', ['saleId' => $sale->id]) }}" class="btn btn-sm btn-outline-primary">Receipt</a> <button wire:click="editSale({{$sale->id}})" class="btn btn-sm btn-outline-secondary">Edit</button> <button wire:click="deleteSale({{$sale->id}})" onclick="confirm('Delete this sale and restore its stock?')||event.stopImmediatePropagation()" class="btn btn-sm btn-outline-danger">Delete</button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-4">No sales found for this filter.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
