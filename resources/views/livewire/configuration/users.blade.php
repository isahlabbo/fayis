<div class="user-manager">
    <style>
        .user-manager { --um-green:#176b4d; --um-soft:#edf7f2; }
        .um-header { padding:1.25rem; border-radius:18px; background:linear-gradient(135deg,#123f32,#238362); color:#fff; }
        .um-card { border:1px solid #e2ebe6; border-radius:16px; box-shadow:0 5px 22px rgba(20,55,44,.07); overflow:hidden; }
        .um-label { color:#64766f; font-size:.72rem; font-weight:700; letter-spacing:.06em; text-transform:uppercase; }
        .um-avatar { width:40px; height:40px; flex:0 0 40px; display:grid; place-items:center; border-radius:11px; background:var(--um-soft); color:var(--um-green); font-weight:700; }
        .um-role { display:inline-block; margin:.12rem; padding:.2rem .5rem; border-radius:999px; background:var(--um-soft); color:var(--um-green); font-size:.72rem; }
        .um-table { min-width:760px; }
        .um-table td { vertical-align:middle; }
        .um-role-grid { display:grid; grid-template-columns:1fr; gap:.5rem; }
        .um-role-option { margin:0; padding:.7rem; display:flex; gap:.65rem; align-items:flex-start; border:1px solid #e2ebe6; border-radius:10px; cursor:pointer; }
        .um-role-option:hover { background:#f8fcfa; border-color:#add3c3; }
        @media(min-width:576px) { .um-role-grid { grid-template-columns:repeat(2,minmax(0,1fr)); } }
        @media(min-width:992px) { .um-header { padding:1.7rem 2rem; } }
    </style>

    <header class="um-header mb-4 d-sm-flex justify-content-between align-items-center">
        <div><small class="text-uppercase" style="opacity:.72;letter-spacing:.12em">Administration</small><h1 class="h3 mt-1 mb-1">User management</h1><p class="mb-0" style="opacity:.8">Create accounts, control status, and assign database roles.</p></div>
        <button wire:click="createUser" class="btn btn-light mt-3 mt-sm-0"><i class="fas fa-user-plus mr-1"></i> Add user</button>
    </header>

    @if(session()->has('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    @if(session()->has('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif

    @if($showForm)
        <form wire:submit.prevent="saveUser" class="card um-card mb-4"><div class="card-body p-3 p-md-4">
            <div class="d-flex justify-content-between align-items-center mb-3"><h2 class="h5 mb-0">{{ $userId ? 'Edit user' : 'Create user' }}</h2><button wire:click="resetForm" type="button" class="close" aria-label="Close">&times;</button></div>
            <div class="row">
                <div class="form-group col-md-6"><label class="um-label" for="um-name">Full name</label><input wire:model.defer="name" id="um-name" class="form-control">@error('name')<small class="text-danger">{{ $message }}</small>@enderror</div>
                <div class="form-group col-md-6"><label class="um-label" for="um-email">Email address</label><input wire:model.defer="email" id="um-email" type="email" class="form-control">@error('email')<small class="text-danger">{{ $message }}</small>@enderror</div>
                <div class="form-group col-md-6"><label class="um-label" for="um-status">Status</label><select wire:model.defer="status" id="um-status" class="form-control"><option>Active</option><option>Inactive</option><option>Suspended</option></select>@error('status')<small class="text-danger">{{ $message }}</small>@enderror</div>
                <div class="form-group col-md-6"><label class="um-label" for="um-legacy-role">Account type <span class="text-lowercase">(legacy compatibility)</span></label><select wire:model.defer="legacyRole" id="um-legacy-role" class="form-control"><option value="staff">Staff</option><option value="guardian">Guardian</option><option value="teacher">Teacher</option><option value="admission_officer">Admission officer</option><option value="exam_officer">Examination officer</option><option value="finance_officer">Finance officer</option><option value="patron">Patron</option><option value="head">Head of school</option><option value="admin">Administrator</option><option value="superadmin">Super administrator</option></select>@error('legacyRole')<small class="text-danger">{{ $message }}</small>@enderror</div>
                <div class="form-group col-md-6"><label class="um-label" for="um-password">{{ $userId ? 'New password (optional)' : 'Password' }}</label><input wire:model.defer="password" id="um-password" type="password" autocomplete="new-password" class="form-control">@error('password')<small class="text-danger">{{ $message }}</small>@enderror</div>
                <div class="form-group col-md-6"><label class="um-label" for="um-password-confirmation">Confirm password</label><input wire:model.defer="password_confirmation" id="um-password-confirmation" type="password" autocomplete="new-password" class="form-control"></div>
            </div>
            <fieldset><legend class="um-label mb-2">Database roles</legend><div class="um-role-grid">
                @foreach($roles as $role)<label class="um-role-option" wire:key="form-role-{{ $role->id }}"><input wire:model.defer="selectedRoles" value="{{ $role->id }}" type="checkbox" class="mt-1"><span><strong>{{ $role->name }}</strong><small class="d-block text-muted">{{ $role->description ?: $role->slug }}</small></span></label>@endforeach
            </div>@error('selectedRoles')<small class="d-block text-danger mt-2">{{ $message }}</small>@enderror</fieldset>
            <div class="mt-4"><button type="submit" class="btn btn-success"><i class="fas fa-save mr-1"></i> {{ $userId ? 'Update user' : 'Create user' }}</button><button wire:click="resetForm" type="button" class="btn btn-light ml-1">Cancel</button></div>
        </div></form>
    @endif

    <div class="card um-card">
        <div class="card-body border-bottom"><div class="row">
            <div class="col-md-8 mb-2 mb-md-0"><label class="um-label" for="um-search">Live search</label><input wire:model.debounce.300ms="search" id="um-search" class="form-control" placeholder="Search name, email, or account type"></div>
            <div class="col-md-4"><label class="um-label" for="um-status-filter">Status</label><select wire:model="statusFilter" id="um-status-filter" class="form-control"><option value="">All statuses</option><option>Active</option><option>Inactive</option><option>Suspended</option></select></div>
        </div></div>
        <div class="table-responsive"><table class="table table-hover mb-0 um-table"><thead class="thead-light"><tr><th>User</th><th>Status</th><th>Account type</th><th>Database roles</th><th class="text-right">Actions</th></tr></thead><tbody>
            @forelse($users as $user)<tr wire:key="managed-user-{{ $user->id }}">
                <td><div class="d-flex align-items-center"><span class="um-avatar mr-2">{{ strtoupper(substr($user->name,0,1)) }}</span><span><strong class="d-block">{{ $user->name }} @if($user->id === Auth::id())<small class="badge badge-success">You</small>@endif</strong><small class="text-muted">{{ $user->email }}</small></span></div></td>
                <td><span class="badge badge-{{ $user->status === 'Active' ? 'success' : ($user->status === 'Suspended' ? 'danger' : 'secondary') }}">{{ $user->status ?: 'Unknown' }}</span></td>
                <td><code>{{ $user->role }}</code></td>
                <td>@forelse($user->accessRoles as $role)<span class="um-role">{{ $role->name }}</span>@empty<span class="text-muted small">No role</span>@endforelse</td>
                <td class="text-right text-nowrap"><button wire:click="editUser({{ $user->id }})" class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i> Edit</button> @if($user->id !== Auth::id())<button wire:click="deleteUser({{ $user->id }})" onclick="confirm('Permanently delete this user?') || event.stopImmediatePropagation()" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>@endif</td>
            </tr>@empty<tr><td colspan="5" class="text-center text-muted py-5">No users found.</td></tr>@endforelse
        </tbody></table></div>
        @if($users->hasPages())<div class="card-footer bg-white">{{ $users->links() }}</div>@endif
    </div>
</div>
