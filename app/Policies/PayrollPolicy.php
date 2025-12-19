<?php

namespace App\Policies;

use App\Models\Payroll;
use App\Models\User;

class PayrollPolicy
{
    public function viewAny(User $user): bool
    {
        // Hanya HRD dan Finance yang boleh lihat list gaji semua orang
        return $user->hasPermissionTo('view-all-payrolls');
    }

    public function view(User $user, Payroll $payroll): bool
    {
        // Karyawan boleh lihat slip gajinya sendiri
        return $user->id === $payroll->user_id || $user->hasPermissionTo('view-all-payrolls');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('manage-payroll');
    }

    public function update(User $user, Payroll $payroll): bool
    {
        // Jika sudah 'paid', tidak boleh diubah lagi
        if ($payroll->status === 'paid') {
            return false;
        }
        return $user->hasPermissionTo('manage-payroll');
    }

    public function approve(User $user): bool
    {
        return $user->hasPermissionTo('approve-payroll');
    }
}
