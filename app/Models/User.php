<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    protected $superAdminState;
    protected $permissionStates = [];

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'status',
        'profile_photo_path',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
    ];

    public function teacher()
    {
        return $this->hasOne(Teacher::class);
    }

    public function userInvoices()
    {
        return $this->hasMany(UserInvoice::class);
    }

    public function profileImage()
    {
        return Storage::url($this->profile_photo_path);
    }

    public function cardRequests()
    {
        return $this->hasMany(CardRequest::class);
    }   

    public function pendingCardRequest() {
        return $this->cardRequests->where('status','Pending')->first();
    }

    public function app()
    {
        return $this->hasOne(App::class);
    }

    /** Database-backed roles. The legacy `role` column remains unchanged. */
    public function accessRoles()
    {
        return $this->morphToMany(Role::class, 'model', 'model_has_roles');
    }

    /** Permissions assigned directly to this user. */
    public function directPermissions()
    {
        return $this->morphToMany(Permission::class, 'model', 'model_has_permissions');
    }

    public function hasAccessRole($role)
    {
        $column = is_numeric($role) ? 'id' : 'slug';

        return $this->accessRoles()->where($column, $role)->exists();
    }

    public function hasPermission($permission)
    {
        // A database-backed superadmin automatically receives current and future permissions.
        if ($this->isSuperAdmin()) {
            return true;
        }

        $column = is_numeric($permission) ? 'id' : 'slug';

        $cacheKey = $column.':'.$permission;
        if (array_key_exists($cacheKey, $this->permissionStates)) {
            return $this->permissionStates[$cacheKey];
        }

        if ($this->relationLoaded('directPermissions') && $this->relationLoaded('accessRoles')) {
            $direct = $this->directPermissions->contains($column, $permission);
            $inherited = $this->accessRoles->contains(function ($role) use ($column, $permission) {
                return $role->relationLoaded('permissions') && $role->permissions->contains($column, $permission);
            });

            return $this->permissionStates[$cacheKey] = $direct || $inherited;
        }

        return $this->permissionStates[$cacheKey] = $this->directPermissions()->where($column, $permission)->exists()
            || $this->accessRoles()->whereHas('permissions', function ($query) use ($column, $permission) {
                $query->where($column, $permission);
            })->exists();
    }

    public function isSuperAdmin()
    {
        if ($this->superAdminState === null) {
            $this->superAdminState = $this->relationLoaded('accessRoles')
                ? $this->accessRoles->contains('slug', 'superadmin')
                : $this->accessRoles()->where('slug', 'superadmin')->exists();
        }

        return $this->superAdminState;
    }

    /** Match a database role while retaining legacy users.role compatibility. */
    public function usesRole($role)
    {
        if ($this->role === $role) {
            return true;
        }

        return $this->relationLoaded('accessRoles')
            ? $this->accessRoles->contains('slug', $role)
            : $this->hasAccessRole($role);
    }
}
