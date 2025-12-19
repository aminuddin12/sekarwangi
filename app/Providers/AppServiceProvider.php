<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Daftarkan Policy secara eksplisit jika auto-discovery gagal
        Gate::policy(\App\Models\User::class, \App\Policies\UserPolicy::class);
        Gate::policy(\App\Models\FinanceRecord::class, \App\Policies\FinanceRecordPolicy::class);
        Gate::policy(\App\Models\InventoryItem::class, \App\Policies\InventoryItemPolicy::class);
        Gate::policy(\App\Models\Order::class, \App\Policies\OrderPolicy::class);
        Gate::policy(\App\Models\Post::class, \App\Policies\PostPolicy::class);

        // Super Admin Bypass (Kunci Utama)
        // Jika user punya role 'super-admin', dia boleh melakukan APA SAJA
        // tanpa perlu cek permission satu per satu.
        Gate::before(function ($user, $ability) {
            return $user->hasRole('super-admin') ? true : null;
        });
    }
}
