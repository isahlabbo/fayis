<?php

namespace App\Http\Livewire\Configuration;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class Users extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $search = '';
    public $statusFilter = '';
    public $userId;
    public $name = '';
    public $email = '';
    public $status = 'Active';
    public $legacyRole = 'staff';
    public $password = '';
    public $password_confirmation = '';
    public $selectedRoles = [];
    public $showForm = false;

    public function boot()
    {
        abort_unless(Auth::check() && Auth::user()->status === 'Active' && Auth::user()->hasPermission('manage-users'), 403);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function createUser()
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function editUser($id)
    {
        $user = User::with('accessRoles')->findOrFail($id);
        $this->userId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->status = $user->status ?: 'Active';
        $this->legacyRole = $user->role ?: 'staff';
        $this->selectedRoles = $user->accessRoles->pluck('id')->map(fn ($id) => (string) $id)->all();
        $this->password = '';
        $this->password_confirmation = '';
        $this->showForm = true;
        $this->resetValidation();
    }

    public function saveUser()
    {
        $editing = (bool) $this->userId;
        $data = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($this->userId)],
            'status' => ['required', Rule::in(['Active', 'Inactive', 'Suspended'])],
            'legacyRole' => ['required', Rule::in(['superadmin', 'admin', 'head', 'admission_officer', 'exam_officer', 'finance_officer', 'patron', 'teacher', 'guardian', 'staff'])],
            'selectedRoles' => ['array'],
            'selectedRoles.*' => ['integer', 'exists:roles,id'],
            'password' => [$editing ? 'nullable' : 'required', 'string', 'min:8', 'confirmed'],
        ]);

        if ($editing && (int) $this->userId === (int) Auth::id() && $data['status'] !== 'Active') {
            $this->addError('status', 'You cannot deactivate your own account.');
            return;
        }
        $superadminRoleId = Role::where('slug', 'superadmin')->value('id');
        if ($editing && (int) $this->userId === (int) Auth::id() && !in_array((string) $superadminRoleId, array_map('strval', $data['selectedRoles'] ?? []), true)) {
            $this->addError('selectedRoles', 'You cannot remove your own superadmin role.');
            return;
        }

        $attributes = [
            'name' => $data['name'],
            'email' => $data['email'],
            'status' => $data['status'],
            // Retained until the remaining legacy roles are migrated to database RBAC.
            'role' => $data['legacyRole'],
        ];
        if ($data['password']) {
            $attributes['password'] = Hash::make($data['password']);
        }

        $user = $editing ? User::findOrFail($this->userId) : new User();
        $user->fill($attributes);
        if (!$editing) {
            $user->email_verified_at = now();
        }
        $user->save();
        $user->accessRoles()->sync($data['selectedRoles'] ?? []);

        $this->resetForm();
        session()->flash('success', $editing ? 'User updated successfully.' : 'User created successfully.');
    }

    public function deleteUser($id)
    {
        if ((int) $id === (int) Auth::id()) {
            session()->flash('error', 'You cannot delete your own account.');
            return;
        }

        $user = User::findOrFail($id);
        try {
            DB::transaction(function () use ($user) {
                $user->accessRoles()->detach();
                $user->directPermissions()->detach();
                $user->delete();
            });
            session()->flash('success', 'User deleted successfully.');
        } catch (QueryException $exception) {
            session()->flash('error', 'This account is linked to school records and cannot be deleted. Suspend it instead.');
        }
    }

    public function resetForm()
    {
        $this->reset(['userId', 'name', 'email', 'password', 'password_confirmation', 'selectedRoles', 'showForm']);
        $this->status = 'Active';
        $this->legacyRole = 'staff';
        $this->resetValidation();
    }

    public function render()
    {
        $users = User::with('accessRoles')
            ->when(trim($this->search), function ($query) {
                $term = '%'.trim($this->search).'%';
                $query->where(function ($query) use ($term) {
                    $query->where('name', 'like', $term)->orWhere('email', 'like', $term)->orWhere('role', 'like', $term);
                });
            })
            ->when($this->statusFilter, fn ($query) => $query->where('status', $this->statusFilter))
            ->orderBy('name')
            ->paginate(15);

        return view('livewire.configuration.users', [
            'users' => $users,
            'roles' => Role::orderBy('name')->get(),
        ]);
    }
}
