<div class="rbac-manager">
    <style>
        .rbac-manager { --rbac-primary: #176b4d; --rbac-soft: #eef8f3; }
        .rbac-hero { background: linear-gradient(135deg, #123f32, #238362); color: #fff; border-radius: 18px; padding: 1.25rem; }
        .rbac-tabs { display: flex; gap: .5rem; overflow-x: auto; padding: .25rem 0; }
        .rbac-tab { white-space: nowrap; border: 0; border-radius: 999px; padding: .65rem 1rem; background: #fff; color: #52615c; box-shadow: 0 2px 12px rgba(20,55,44,.08); }
        .rbac-tab.active { background: var(--rbac-primary); color: #fff; }
        .rbac-card { border: 0; border-radius: 16px; box-shadow: 0 5px 24px rgba(20,55,44,.08); overflow: hidden; }
        .rbac-label { font-size: .75rem; font-weight: 700; color: #687972; letter-spacing: .04em; text-transform: uppercase; }
        .rbac-item { border: 1px solid #e7eeeb; border-radius: 12px; padding: .85rem; transition: .15s ease; }
        .rbac-item:hover { border-color: #abd4c3; background: #fbfefc; }
        .rbac-toggle { width: 1.15rem; height: 1.15rem; accent-color: var(--rbac-primary); }
        .rbac-badge { display: inline-block; border-radius: 999px; background: var(--rbac-soft); color: var(--rbac-primary); padding: .2rem .55rem; font-size: .75rem; }
        [wire\:loading] { opacity: .65; }
        @media (min-width: 768px) { .rbac-hero { padding: 1.75rem 2rem; } }
    </style>

    <section class="rbac-hero mb-4">
        <div class="d-md-flex justify-content-between align-items-center">
            <div>
                <div class="small text-uppercase mb-2" style="opacity:.75;letter-spacing:.12em">System configuration</div>
                <h2 class="h3 mb-2">Roles & permissions</h2>
                <p class="mb-0" style="opacity:.82">Create access rules and assign them without changing the application's legacy roles.</p>
            </div>
            <i class="fas fa-user-shield d-none d-md-block" style="font-size:3rem;opacity:.22"></i>
        </div>
    </section>

    @if (session()->has('success'))
        <div class="alert alert-success" role="alert">{{ session('success') }}</div>
    @endif

    <nav class="rbac-tabs mb-4" aria-label="Access control sections">
        <button wire:click="selectTab('roles')" class="rbac-tab {{ $activeTab === 'roles' ? 'active' : '' }}"><i class="fas fa-users-cog mr-1"></i> Roles</button>
        <button wire:click="selectTab('permissions')" class="rbac-tab {{ $activeTab === 'permissions' ? 'active' : '' }}"><i class="fas fa-key mr-1"></i> Permissions</button>
        <button wire:click="selectTab('role-permissions')" class="rbac-tab {{ $activeTab === 'role-permissions' ? 'active' : '' }}"><i class="fas fa-link mr-1"></i> Role permissions</button>
        <button wire:click="selectTab('user-access')" class="rbac-tab {{ $activeTab === 'user-access' ? 'active' : '' }}"><i class="fas fa-user-lock mr-1"></i> User access</button>
    </nav>

    <div class="card rbac-card mb-4">
        <div class="card-body">
            <label class="rbac-label" for="rbac-search">Live search</label>
            <div class="input-group">
                <div class="input-group-prepend"><span class="input-group-text bg-white border-right-0"><i class="fas fa-search text-muted"></i></span></div>
                <input wire:model.debounce.300ms="search" id="rbac-search" class="form-control border-left-0" placeholder="Search by name, slug or email">
                <div class="input-group-append" wire:loading wire:target="search"><span class="input-group-text bg-white"><i class="fas fa-circle-notch fa-spin"></i></span></div>
            </div>
        </div>
    </div>

    @if($activeTab === 'roles')
        <div class="row">
            <div class="col-lg-4 mb-4">
                <form wire:submit.prevent="saveRole" class="card rbac-card">
                    <div class="card-body">
                        <h3 class="h5 mb-3">{{ $roleId ? 'Edit role' : 'Create a role' }}</h3>
                        <div class="form-group"><label class="rbac-label">Name</label><input wire:model="roleName" class="form-control" placeholder="e.g. Librarian">@error('roleName')<small class="text-danger">{{ $message }}</small>@enderror</div>
                        <div class="form-group"><label class="rbac-label">Slug</label><input wire:model="roleSlug" class="form-control" placeholder="e.g. librarian">@error('roleSlug')<small class="text-danger">{{ $message }}</small>@enderror</div>
                        <div class="form-group"><label class="rbac-label">Description</label><textarea wire:model="roleDescription" class="form-control" rows="3"></textarea>@error('roleDescription')<small class="text-danger">{{ $message }}</small>@enderror</div>
                        <button class="btn btn-success" type="submit"><i class="fas fa-save mr-1"></i> {{ $roleId ? 'Update' : 'Create' }}</button>
                        @if($roleId)<button wire:click="resetRoleForm" type="button" class="btn btn-light">Cancel</button>@endif
                    </div>
                </form>
            </div>
            <div class="col-lg-8">
                <div class="card rbac-card"><div class="card-body">
                    <h3 class="h5 mb-3">Available roles <span class="rbac-badge">{{ $roles->count() }}</span></h3>
                    @forelse($roles as $role)
                        <div class="rbac-item mb-2 d-sm-flex justify-content-between align-items-center" wire:key="role-{{ $role->id }}">
                            <div class="mb-2 mb-sm-0"><strong>{{ $role->name }}</strong> <code>{{ $role->slug }}</code><div class="small text-muted">{{ $role->description ?: 'No description' }} · {{ $role->permissions_count }} permissions · {{ $role->users_count }} users</div></div>
                            <div class="text-nowrap"><button wire:click="editRole({{ $role->id }})" class="btn btn-sm btn-outline-primary">Edit</button> <button wire:click="deleteRole({{ $role->id }})" onclick="confirm('Delete this role and all its assignments?') || event.stopImmediatePropagation()" class="btn btn-sm btn-outline-danger">Delete</button></div>
                        </div>
                    @empty <div class="text-center text-muted py-4">No roles found. Create the first role.</div> @endforelse
                </div></div>
            </div>
        </div>
    @elseif($activeTab === 'permissions')
        <div class="row">
            <div class="col-lg-4 mb-4">
                <form wire:submit.prevent="savePermission" class="card rbac-card"><div class="card-body">
                    <h3 class="h5 mb-3">{{ $permissionId ? 'Edit permission' : 'Create a permission' }}</h3>
                    <div class="form-group"><label class="rbac-label">Name</label><input wire:model="permissionName" class="form-control" placeholder="e.g. View reports">@error('permissionName')<small class="text-danger">{{ $message }}</small>@enderror</div>
                    <div class="form-group"><label class="rbac-label">Slug</label><input wire:model="permissionSlug" class="form-control" placeholder="e.g. view-reports">@error('permissionSlug')<small class="text-danger">{{ $message }}</small>@enderror</div>
                    <div class="form-group"><label class="rbac-label">Description</label><textarea wire:model="permissionDescription" class="form-control" rows="3"></textarea>@error('permissionDescription')<small class="text-danger">{{ $message }}</small>@enderror</div>
                    <button class="btn btn-success" type="submit"><i class="fas fa-save mr-1"></i> {{ $permissionId ? 'Update' : 'Create' }}</button>
                    @if($permissionId)<button wire:click="resetPermissionForm" type="button" class="btn btn-light">Cancel</button>@endif
                </div></form>
            </div>
            <div class="col-lg-8"><div class="card rbac-card"><div class="card-body">
                <h3 class="h5 mb-3">Available permissions <span class="rbac-badge">{{ $permissions->count() }}</span></h3>
                @forelse($permissions as $permission)
                    <div class="rbac-item mb-2 d-sm-flex justify-content-between align-items-center" wire:key="permission-{{ $permission->id }}">
                        <div class="mb-2 mb-sm-0"><strong>{{ $permission->name }}</strong> <code>{{ $permission->slug }}</code><div class="small text-muted">{{ $permission->description ?: 'No description' }} · Used by {{ $permission->roles_count }} roles</div></div>
                        <div class="text-nowrap"><button wire:click="editPermission({{ $permission->id }})" class="btn btn-sm btn-outline-primary">Edit</button> <button wire:click="deletePermission({{ $permission->id }})" onclick="confirm('Delete this permission and all its assignments?') || event.stopImmediatePropagation()" class="btn btn-sm btn-outline-danger">Delete</button></div>
                    </div>
                @empty <div class="text-center text-muted py-4">No permissions found.</div> @endforelse
            </div></div></div>
        </div>
    @elseif($activeTab === 'role-permissions')
        <div class="row">
            <div class="col-lg-4 mb-4"><div class="card rbac-card"><div class="card-body">
                <label class="rbac-label" for="selected-role">Select role</label>
                <select wire:model="selectedRoleId" id="selected-role" class="form-control"><option value="">Choose a role</option>@foreach($roles as $role)<option value="{{ $role->id }}">{{ $role->name }}</option>@endforeach</select>
                <p class="small text-muted mt-3 mb-0">Ticking an item grants it immediately. Unticking revokes it immediately.</p>
            </div></div></div>
            <div class="col-lg-8"><div class="card rbac-card"><div class="card-body">
                <h3 class="h5 mb-3">Permissions for {{ optional($selectedRole)->name ?: 'selected role' }}</h3>
                @if($selectedRole)
                    @forelse($permissions as $permission)
                        <label class="rbac-item mb-2 d-flex align-items-start" wire:key="role-permission-{{ $permission->id }}" style="cursor:pointer">
                            <input wire:click="toggleRolePermission({{ $permission->id }})" type="checkbox" class="rbac-toggle mt-1 mr-3" {{ $selectedRole->permissions->contains($permission->id) ? 'checked' : '' }}>
                            <span><strong>{{ $permission->name }}</strong><span class="d-block small text-muted">{{ $permission->description ?: $permission->slug }}</span></span>
                        </label>
                    @empty <p class="text-muted">Create permissions before assigning them.</p> @endforelse
                @else <p class="text-muted">Create and select a role to manage its permissions.</p> @endif
            </div></div></div>
        </div>
    @else
        <div class="row">
            <div class="col-lg-4 mb-4"><div class="card rbac-card"><div class="card-body">
                <label class="rbac-label" for="selected-user">Select user</label>
                <select wire:model="selectedUserId" id="selected-user" class="form-control"><option value="">Choose a user</option>@foreach($users as $user)<option value="{{ $user->id }}">{{ $user->name }} — {{ $user->email }}</option>@endforeach</select>
                @if($selectedUser)<div class="mt-3 p-3 rounded" style="background:var(--rbac-soft)"><strong>{{ $selectedUser->name }}</strong><div class="small text-muted">Legacy role: {{ $selectedUser->role ?: 'none' }} (unchanged)</div></div>@endif
            </div></div></div>
            <div class="col-lg-8">
                @if($selectedUser)
                    <div class="card rbac-card mb-4"><div class="card-body">
                        <h3 class="h5 mb-1">User roles</h3><p class="small text-muted">Database-backed roles assigned in addition to the legacy role.</p>
                        @forelse($roles as $role)<label class="rbac-item mb-2 d-flex align-items-start" wire:key="user-role-{{ $role->id }}" style="cursor:pointer"><input wire:click="toggleUserRole({{ $role->id }})" type="checkbox" class="rbac-toggle mt-1 mr-3" {{ $selectedUser->accessRoles->contains($role->id) ? 'checked' : '' }}><span><strong>{{ $role->name }}</strong><span class="d-block small text-muted">{{ $role->permissions_count }} inherited permissions</span></span></label>@empty<p class="text-muted">No roles available.</p>@endforelse
                    </div></div>
                    <div class="card rbac-card"><div class="card-body">
                        <h3 class="h5 mb-1">Direct permissions</h3><p class="small text-muted">These grants apply directly to this user, independently of roles.</p>
                        @forelse($permissions as $permission)<label class="rbac-item mb-2 d-flex align-items-start" wire:key="user-permission-{{ $permission->id }}" style="cursor:pointer"><input wire:click="toggleUserPermission({{ $permission->id }})" type="checkbox" class="rbac-toggle mt-1 mr-3" {{ $selectedUser->directPermissions->contains($permission->id) ? 'checked' : '' }}><span><strong>{{ $permission->name }}</strong><span class="d-block small text-muted">{{ $permission->slug }}</span></span></label>@empty<p class="text-muted">No permissions available.</p>@endforelse
                    </div></div>
                @else <div class="card rbac-card"><div class="card-body text-center text-muted py-5">Select a user to manage access.</div></div> @endif
            </div>
        </div>
    @endif
</div>
