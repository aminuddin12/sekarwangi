<?php

namespace App\Policies;

use App\Models\FinanceRecord;
use App\Models\User;

class FinanceRecordPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view-finance');
    }

    public function view(User $user, FinanceRecord $record): bool
    {
        // User biasa hanya bisa lihat record yang dia buat
        // Bendahara/Admin bisa lihat semua
        return $user->id === $record->recorded_by || $user->hasPermissionTo('view-finance');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create-finance');
    }

    public function update(User $user, FinanceRecord $record): bool
    {
        // Jika status sudah 'reconciled' (tutup buku), TIDAK BOLEH diedit siapa pun
        if ($record->status === 'reconciled') {
            return false;
        }

        // Jika status 'cleared' (sudah diverifikasi), hanya Auditor yang boleh edit
        if ($record->status === 'cleared' && !$user->hasPermissionTo('audit-finance')) {
            return false;
        }

        return $user->hasPermissionTo('edit-finance');
    }

    public function delete(User $user, FinanceRecord $record): bool
    {
        // Data keuangan sebaiknya Soft Delete atau Void, bukan dihapus permanen
        // Kecuali status masih 'pending'
        if ($record->status !== 'pending') {
            return false;
        }

        return $user->hasPermissionTo('delete-finance');
    }

    /**
     * Hak khusus untuk Verifikasi Transaksi
     */
    public function verify(User $user): bool
    {
        return $user->hasPermissionTo('verify-finance');
    }
}
