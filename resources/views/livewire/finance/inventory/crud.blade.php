<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div>
            <h5 class="mb-0">{{ $isEdit ? 'Edit Inventory Item' : 'Create Inventory Item' }}</h5>
        </div>
        <div>
            <button wire:click="resetForm" wire:click.prevent="createItem" class="btn btn-sm btn-outline-primary">
                <i class="fas fa-plus"></i> New Item
            </button>
        </div>
    </div>

    @if($showForm)
        <div class="card-body">
            <form wire:submit.prevent="saveItem">
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="inventory_category_id">Category</label>
                        <select wire:model="inventory_category_id" id="inventory_category_id" class="form-control">
                            <option value="">Select category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('inventory_category_id') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group col-md-8">
                        <label for="name">Item Name</label>
                        <input wire:model="name" id="name" class="form-control" type="text">
                        @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="form-row mt-3">
                    <div class="form-group col-md-12">
                        <label for="description">Description</label>
                        <textarea wire:model="description" id="description" class="form-control" rows="2"></textarea>
                        @error('description') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="button" wire:click="resetForm" class="btn btn-light mr-2">Cancel</button>
                    <button type="submit" class="btn btn-success">{{ $isEdit ? 'Update Item' : 'Save Item' }}</button>
                </div>
            </form>
        </div>
    @endif
</div>
