<div>
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-0">Rents</h5>
                <p class="mb-0 text-muted">Manage item rent transactions for teachers.</p>
            </div>
        </div>
        <div class="card-body">
            <form wire:submit.prevent="saveRent">
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="teacher_id">Teacher</label>
                        <select wire:model="teacher_id" id="teacher_id" class="form-control">
                            <option value="">Select teacher</option>
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->id }}">{{ optional($teacher->user)->name ?? 'Teacher #' . $teacher->id }}</option>
                            @endforeach
                        </select>
                        @error('teacher_id') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group col-md-4">
                        <label for="item_id">Item</label>
                        <select wire:model="item_id" id="item_id" class="form-control">
                            <option value="">Select inventory item</option>
                            @foreach($items as $item)
                                <option value="{{ $item->id }}">{{ $item->name }} ({{ $item->sku }}) - {{ $item->quantity }} available</option>
                            @endforeach
                        </select>
                        @error('item_id') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="form-row mt-3">
                    <div class="form-group col-md-2">
                        <label for="quantity">Quantity</label>
                        <input wire:model="quantity" id="quantity" class="form-control" type="number" min="1">
                        @error('quantity') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group col-md-4">
                        <label for="usage_date">Rent date</label>
                        <input wire:model="usage_date" id="usage_date" class="form-control" type="date">
                        @error('usage_date') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="form-row mt-3">
                    <div class="form-group col-md-6">
                        <label for="evidence">Evidence</label>
                        <textarea wire:model="evidence" id="evidence" class="form-control" rows="3"></textarea>
                        @error('evidence') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group col-md-6">
                        <label for="notes">Notes</label>
                        <textarea wire:model="notes" id="notes" class="form-control" rows="3"></textarea>
                        @error('notes') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-success"><i class="fas fa-check"></i> Record Rent</button>
                </div>
            </form>
        </div>
    </div>
</div>
