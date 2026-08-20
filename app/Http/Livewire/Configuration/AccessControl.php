<?php

namespace App\Http\Livewire\Configuration;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Str;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class AccessControl extends Component
{
    public $activeTab = 'roles';
    public $search = '';
    public $roleId;
    public $roleName = '';
    public $roleSlug = '';
    public $roleDescription = '';
    public $permissionId;
    public $permissionName = '';
    public $permissionSlug = '';
    public $permissionDescription = '';
    public $selectedRoleId;
    public $selectedUserId;

    protected $queryString = ['activeTab' => ['except' => 'roles']];

    public function boot()
    {
        abort_unless(Auth::check() && Auth::user()->status === 'Active' && Auth::user()->hasPermission('manage-access-control'), 403);
    }

    public function mount($activeTab = 'roles')
    {
        $this->activeTab = $activeTab;
        $this->selectedRoleId = optional(Role::orderBy('name')->first())->id;
        $this->selectedUserId = optional(User::orderBy('name')->first())->id;
    }

    public function selectTab($tab)
    {
        abort_unless(in_array($tab, ['roles', 'permissions', 'role-permissions', 'user-access'], true), 404);
        $this->activeTab = $tab;
        $this->search = '';
        $this->resetValidation();
    }

    public function updatedRoleName($value)
    {
        if (!$this->roleId) {
            $this->roleSlug = Str::slug($value);
        }
    }

    public function updatedPermissionName($value)
    {
        if (!$this->permissionId) {
            $this->permissionSlug = Str::slug($value);
        }
    }

    public function saveRole()
    {
        $data = $this->validate([
            'roleName' => 'required|string|max:100|unique:roles,name,'.($this->roleId ?: 'NULL'),
            'roleSlug' => 'required|alpha_dash|max:100|unique:roles,slug,'.($this->roleId ?: 'NULL'),
            'roleDescription' => 'nullable|string|max:1000',
        ]);

        Role::updateOrCreate(['id' => $this->roleId], [
            'name' => $data['roleName'],
            'slug' => Str::lower($data['roleSlug']),
            'description' => $data['roleDescription'] ?: null,
        ]);
        $this->resetRoleForm();
        session()->flash('success', 'Role saved successfully.');
    }

    public function editRole($id)
    {
        $role = Role::findOrFail($id);
        $this->roleId = $role->id;
        $this->roleName = $role->name;
        $this->roleSlug = $role->slug;
        $this->roleDescription = $role->description;
    }

    public function deleteRole($id)
    {
        Role::findOrFail($id)->delete();
        if ((int) $this->selectedRoleId === (int) $id) {
            $this->selectedRoleId = optional(Role::orderBy('name')->first())->id;
        }
        $this->resetRoleForm();
        session()->flash('success', 'Role and its assignments were removed.');
    }

    public function resetRoleForm()
    {
        $this->reset(['roleId', 'roleName', 'roleSlug', 'roleDescription']);
        $this->resetValidation();
    }

    public function savePermission()
    {
        $data = $this->validate([
            'permissionName' => 'required|string|max:100|unique:permissions,name,'.($this->permissionId ?: 'NULL'),
            'permissionSlug' => 'required|alpha_dash|max:100|unique:permissions,slug,'.($this->permissionId ?: 'NULL'),
            'permissionDescription' => 'nullable|string|max:1000',
        ]);

        Permission::updateOrCreate(['id' => $this->permissionId], [
            'name' => $data['permissionName'],
            'slug' => Str::lower($data['permissionSlug']),
            'description' => $data['permissionDescription'] ?: null,
        ]);
        $this->resetPermissionForm();
        session()->flash('success', 'Permission saved successfully.');
    }

    public function editPermission($id)
    {
        $permission = Permission::findOrFail($id);
        $this->permissionId = $permission->id;
        $this->permissionName = $permission->name;
        $this->permissionSlug = $permission->slug;
        $this->permissionDescription = $permission->description;
    }

    public function deletePermission($id)
    {
        Permission::findOrFail($id)->delete();
        $this->resetPermissionForm();
        session()->flash('success', 'Permission and its assignments were removed.');
    }

    public function resetPermissionForm()
    {
        $this->reset(['permissionId', 'permissionName', 'permissionSlug', 'permissionDescription']);
        $this->resetValidation();
    }

    public function toggleRolePermission($permissionId)
    {
        $role = Role::findOrFail($this->selectedRoleId);
        $role->permissions()->toggle($permissionId);
    }

    public function toggleUserRole($roleId)
    {
        User::findOrFail($this->selectedUserId)->accessRoles()->toggle($roleId);
    }

    public function toggleUserPermission($permissionId)
    {
        User::findOrFail($this->selectedUserId)->directPermissions()->toggle($permissionId);
    }

    public function render()
    {
        $term = trim($this->search);
        $roles = Role::withCount(['permissions', 'users'])
            ->when($term, fn ($query) => $query->where('name', 'like', "%{$term}%")->orWhere('slug', 'like', "%{$term}%"))
            ->orderBy('name')->get();
        $permissions = Permission::withCount('roles')
            ->when($term, fn ($query) => $query->where('name', 'like', "%{$term}%")->orWhere('slug', 'like', "%{$term}%"))
            ->orderBy('name')->get();
        $users = User::when($term, fn ($query) => $query->where('name', 'like', "%{$term}%")->orWhere('email', 'like', "%{$term}%"))
            ->orderBy('name')->limit(100)->get();

        $selectedRole = $this->selectedRoleId ? Role::with('permissions')->find($this->selectedRoleId) : null;
        $selectedUser = $this->selectedUserId ? User::with(['accessRoles.permissions', 'directPermissions'])->find($this->selectedUserId) : null;

        return view('livewire.configuration.access-control', compact('roles', 'permissions', 'users', 'selectedRole', 'selectedUser'));
    }
}
