<div class="sa-dashboard">
    <div class="sa-welcome mb-4">
        <div>
            <span class="sa-eyebrow">System administration</span>
            <h1>Welcome back, {{ explode(' ', Auth::user()->name)[0] }}</h1>
            <p>Manage access rules and keep every account appropriately protected.</p>
        </div>
        <i class="fas fa-shield-alt"></i>
    </div>

    <div class="row">
        <div class="col-6 col-lg-3 mb-3">
            <a class="sa-stat" href="{{ route('configuration.role.index') }}">
                <span class="sa-stat-icon"><i class="fas fa-users-cog"></i></span>
                <strong>{{ App\Models\Role::count() }}</strong><small>Roles</small>
            </a>
        </div>
        <div class="col-6 col-lg-3 mb-3">
            <a class="sa-stat" href="{{ route('configuration.permission.index') }}">
                <span class="sa-stat-icon"><i class="fas fa-key"></i></span>
                <strong>{{ App\Models\Permission::count() }}</strong><small>Permissions</small>
            </a>
        </div>
        <div class="col-6 col-lg-3 mb-3">
            <a class="sa-stat" href="{{ route('configuration.role.permissions') }}">
                <span class="sa-stat-icon"><i class="fas fa-link"></i></span>
                <strong>{{ App\Models\RoleHasPermission::count() }}</strong><small>Role grants</small>
            </a>
        </div>
        <div class="col-6 col-lg-3 mb-3">
            <a class="sa-stat" href="{{ route('configuration.permission.users') }}">
                <span class="sa-stat-icon"><i class="fas fa-user-lock"></i></span>
                <strong>{{ App\Models\User::count() }}</strong><small>Users</small>
            </a>
        </div>
    </div>

    <div class="card border-0 shadow-sm mt-2" style="border-radius:18px">
        <div class="card-body p-4">
            <h2 class="h5">Quick access</h2>
            <p class="text-muted">Choose an access-control workspace to continue.</p>
            <div class="d-flex flex-wrap" style="gap:.6rem">
                <a class="btn btn-success" href="{{ route('configuration.role.index') }}">Manage roles</a>
                <a class="btn btn-outline-success" href="{{ route('configuration.permission.index') }}">Manage permissions</a>
                <a class="btn btn-outline-success" href="{{ route('configuration.role.permissions') }}">Assign permissions</a>
                <a class="btn btn-outline-success" href="{{ route('configuration.permission.users') }}">Manage user roles</a>
            </div>
        </div>
    </div>
</div>
