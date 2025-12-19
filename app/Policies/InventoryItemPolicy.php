<?php

namespace App\Policies;

use App\Models\InventoryItem;
use App\Models\User;

class InventoryItemPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view-inventory');
    }

    public function view(User $user, InventoryItem $item): bool
    {
        return $user->hasPermissionTo('view-inventory');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create-inventory');
    }

    public function update(User $user, InventoryItem $item): bool
    {
        return $user->hasPermissionTo('edit-inventory');
    }

    public function delete(User $user, InventoryItem $item): bool
    {
        // Barang yang masih ada stok > 0 tidak boleh dihapus sembarangan
        if ($item->quantity > 0 && !$user->hasRole('super-admin')) {
            return false;
        }
        return $user->hasPermissionTo('delete-inventory');
    }

    // Fitur Stock Opname / Adjustment
    public function adjustStock(User $user): bool
    {
        return $user->hasPermissionTo('adjust-inventory-stock');
    }
}
