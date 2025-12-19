<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;

class OrderPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('manage-orders');
    }

    public function view(User $user, Order $order): bool
    {
        // Pembeli bisa lihat ordernya sendiri
        return $user->id === $order->user_id || $user->hasPermissionTo('manage-orders');
    }

    public function update(User $user, Order $order): bool
    {
        // Pembeli tidak bisa edit order yang sudah diproses
        if ($user->id === $order->user_id) {
            return $order->status === 'pending';
        }

        return $user->hasPermissionTo('manage-orders');
    }

    // Admin mengubah status order (Kirim, Selesai, Batal)
    public function manageStatus(User $user): bool
    {
        return $user->hasPermissionTo('manage-orders');
    }
}
