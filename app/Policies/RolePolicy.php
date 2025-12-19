<?php

namespace App\Policies;

use App\Models\User;
use Spatie\Permission\Models\Role;

class RolePolicy
{
    // Hanya Super Admin yang boleh menyentuh Role & Permission
    public function before(User $user, $ability)
    {
        if ($user->hasRole('super-admin')) {
            return true;
        }
        return null; // Lanjut ke cek permission spesifik
    }

    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view-roles');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create-roles');
    }

    public function update(User $user, Role $role): bool
    {
        // Role 'super-admin' tidak boleh diedit sembarangan
        if ($role->name === 'super-admin') {
            return false;
        }
        return $user->hasPermissionTo('edit-roles');
    }

    public function delete(User $user, Role $role): bool
    {
        // Role 'super-admin' tidak boleh dihapus
        if ($role->name === 'super-admin') {
            return false;
        }
        return $user->hasPermissionTo('delete-roles');
    }
}
