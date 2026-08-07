<div>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-1">Inventory Categories</h4>
            <p class="text-muted">Manage categories used to group inventory items.</p>
        </div>
        <div>
            @if(Auth::user()->role == 'finance')
            <button wire:click="createCategory" class="btn btn-primary"><i class="fas fa-plus"></i> New Category</button>
            @endif
            <a href="{{ route('finance.inventory.categories.pdf', ['searchTerm' => $searchTerm]) }}" class="btn btn-outline-secondary"><i class="fas fa-download"></i> Download PDF</a>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <form class="form-row align-items-end">
                <div class="form-group col-md-6">
                    <label for="searchTerm">Search categories</label>
                    <input wire:model="searchTerm" id="searchTerm" class="form-control" placeholder="Name">
                </div>
            </form>

            @if($showForm)
                <hr>
                <form wire:submit.prevent="saveCategory">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="name">Category name</label>
                            <input wire:model="name" id="name" class="form-control" type="text">
                            @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label for="description">Description</label>
                            <input wire:model="description" id="description" class="form-control" type="text">
                            @error('description') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="button" wire:click="resetForm" class="btn btn-light mr-2">Cancel</button>
                        <button type="submit" class="btn btn-success">{{ $isEdit ? 'Update Category' : 'Save Category' }}</button>
                    </div>
                </form>
            @endif
        </div>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="thead-light">
                    <tr>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Items</th>
                        <th>Created</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                        <tr>
                            <td>{{ $category->name }}</td>
                            <td>{{ Str::limit($category->description, 80) }}</td>
                            <td>{{ $category->items()->count() }}</td>
                            <td>{{ $category->created_at->format('Y-m-d') }}</td>
                            <td class="text-right">
                                <button wire:click="editCategory({{ $category->id }})" class="btn btn-sm btn-outline-primary">Edit</button>
                                <button wire:click="deleteCategory({{ $category->id }})" class="btn btn-sm btn-outline-danger">Delete</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4">No categories found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>