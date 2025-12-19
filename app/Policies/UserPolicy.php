<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    /**
     * Tentukan apakah user bisa melihat daftar pengguna.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view-users');
    }

    /**
     * Tentukan apakah user bisa melihat detail pengguna tertentu.
     */
    public function view(User $user, User $model): bool
    {
        // User bisa melihat profilnya sendiri ATAU punya izin 'view-users'
        return $user->id === $model->id || $user->hasPermissionTo('view-users');
    }

    /**
     * Tentukan apakah user bisa membuat pengguna baru (Admin).
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create-users');
    }

    /**
     * Tentukan apakah user bisa mengedit pengguna lain.
     */
    public function update(User $user, User $model): bool
    {
        // User bisa edit profil sendiri, TAPI tidak bisa edit Role/Status sendiri
        if ($user->id === $model->id) {
            return true;
        }

        // Super Admin bisa edit siapa saja
        if ($user->hasRole('super-admin')) {
            return true;
        }

        // Admin bisa edit user biasa, tapi tidak sesama Admin/Super Admin
        if ($user->hasRole('admin') && !$model->hasRole(['super-admin', 'admin'])) {
            return true;
        }

        return $user->hasPermissionTo('edit-users');
    }

    /**
     * Tentukan apakah user bisa menghapus pengguna.
     */
    public function delete(User $user, User $model): bool
    {
        // Tidak boleh hapus diri sendiri
        if ($user->id === $model->id) {
            return false;
        }

        return $user->hasPermissionTo('delete-users');
    }

    /**
     * Tentukan apakah user bisa mengubah Role/Permission pengguna lain.
     * (Hanya Super Admin yang boleh)
     */
    public function manageRoles(User $user): bool
    {
        return $user->hasRole('super-admin');
    }
}
