<div>
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-0">Usage</h5>
                <p class="mb-0 text-muted">View stock availability and usage summary statistics.</p>
            </div>
            <div>
                <a href="#" class="btn btn-primary"><i class="fas fa-chart-line"></i> Usage Report</a>
            </div>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="border rounded p-3">
                        <div class="text-muted">Available Items</div>
                        <div class="h3 mt-2">{{ $items->count() }}</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="border rounded p-3">
                        <div class="text-muted">Total Stock</div>
                        <div class="h3 mt-2">{{ $items->sum('quantity') }}</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="border rounded p-3">
                        <div class="text-muted">Lowest Stock</div>
                        <div class="h3 mt-2">{{ $items->min('quantity') ?? 0 }}</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="border rounded p-3">
                        <div class="text-muted">Highest Stock</div>
                        <div class="h3 mt-2">{{ $items->max('quantity') ?? 0 }}</div>
                    </div>
                </div>
            </div>

            <form wire:submit.prevent="saveUsage">
                <div class="form-row">
                    @if($usage_type === 'sale')
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
                    @else
                        <div class="form-group col-md-3">
                            <label for="teacher_id">Teacher</label>
                            <select wire:model="teacher_id" id="teacher_id" class="form-control">
                                <option value="">Select teacher</option>
                                @foreach($teachers as $teacher)
                                    <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                                @endforeach
                            </select>
                            @error('teacher_id') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    @endif
                    <div class="form-group col-md-3">
                        <label for="item_id">Item</label>
                        <select wire:model="item_id" id="item_id" class="form-control">
                            <option value="">Select inventory item</option>
                            @foreach($items as $item)
                                <option value="{{ $item->id }}">{{ $item->name }} ({{ $item->sku }}) - {{ $item->quantity }} available</option>
                            @endforeach
                        </select>
                        @error('item_id') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group col-md-3">
                        <label for="inventory_stock_id">Stock batch</label>
                        <select wire:model="inventory_stock_id" id="inventory_stock_id" class="form-control">
                            <option value="">Select batch</option>
                            @foreach($stocks as $stock)
                                <option value="{{ $stock->id }}">Batch {{ $stock->id }} - {{ $stock->remaining_quantity }} remaining @ {{ number_format($stock->unit_cost, 2) }}</option>
                            @endforeach
                        </select>
                        @error('inventory_stock_id') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="form-row mt-3">
                    <div class="form-group col-md-2">
                        <label for="usage_type">Usage type</label>
                        <select wire:model="usage_type" id="usage_type" class="form-control">
                            <option value="sale">Sale</option>
                            <option value="rent">Rent</option>
                        </select>
                        @error('usage_type') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group col-md-2">
                        <label for="quantity">Quantity</label>
                        <input wire:model="quantity" id="quantity" class="form-control" type="number" min="1">
                        @error('quantity') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group col-md-2">
                        <label for="unit_cost">Unit cost</label>
                        <input wire:model="unit_cost" id="unit_cost" class="form-control" type="number" step="0.01">
                        @error('unit_cost') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group col-md-3">
                        <label for="receipt_number">Receipt / Evidence No.</label>
                        <input wire:model="receipt_number" id="receipt_number" class="form-control" type="text">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="usage_date">Usage date</label>
                        <input wire:model="usage_date" id="usage_date" class="form-control" type="date">
                        @error('usage_date') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="form-row mt-3">
                    <div class="form-group col-md-6">
                        <label for="evidence">Evidence / Notes</label>
                        <textarea wire:model="evidence" id="evidence" class="form-control" rows="3"></textarea>
                        @error('evidence') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group col-md-6">
                        <label for="notes">Usage details</label>
                        <textarea wire:model="notes" id="notes" class="form-control" rows="3"></textarea>
                        @error('notes') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-success"><i class="fas fa-check"></i> Record Usage</button>
                </div>
            </form>
        </div>
    </div>
</div>