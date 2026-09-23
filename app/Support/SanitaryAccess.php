<?php

namespace App\Support;

use App\Models\User;

class SanitaryAccess
{
    public static function canManage(?User $user): bool
    {
        return self::eligible($user) && $user->usesRole('admin');
    }

    public static function canView(?User $user): bool
    {
        return self::eligible($user)
            && ($user->usesRole('admin') || $user->usesRole('head') || $user->usesRole('mentor'));
    }

    private static function eligible(?User $user): bool
    {
        // Sanitary material access explicitly excludes super administrators,
        // including users who also hold an administrator or monitoring role.
        return $user && $user->status === 'Active'
            && !$user->isSuperAdmin() && !$user->usesRole('superadmin');
    }
}
