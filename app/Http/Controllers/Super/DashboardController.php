<?php

namespace App\Http\Controllers\Super;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

// Import Widgets
use App\Widgets\UserCount;
use App\Widgets\SummaryCount;
use App\Widgets\VisitorCount;
use App\Widgets\VisitorChart;
use App\Widgets\VisitorActivity;
use App\Widgets\ScheduleIncoming;
use App\Widgets\ScheduleFull;
use App\Widgets\SystemHealth;

class DashboardController extends Controller
{
    /**
     * Menampilkan dashboard modular berdasarkan permission user.
     */
    public function index(): Response
    {
        $user = Auth::user();

        $smallWidgets = [];
        $largeWidgets = [];

        // --- GROUP 1: SMALL WIDGETS (Stats Cards) ---

        // 1. User Stats (HR & Admin)
        if ($user->can('view-users') || $user->hasRole('Super Admin')) {
            $smallWidgets[] = (new UserCount)->build();
        }

        // 2. Finance / Summary (Finance & Admin)
        if ($user->can('view-finance-dashboard') || $user->hasRole('Super Admin')) {
            $smallWidgets[] = (new SummaryCount)->build();
        }

        // 3. Visitor Count (Marketing & Admin)
        if ($user->can('view-analytics-dashboard') || $user->hasRole('Super Admin')) {
            $smallWidgets[] = (new VisitorCount)->build();
        }

        // 4. Monthly Schedule Count (General User)
        if ($user->can('view-schedules') || $user->hasRole('Super Admin')) {
            $smallWidgets[] = (new ScheduleFull)->build();
        }


        // --- GROUP 2: LARGE WIDGETS (Charts, Tables, Lists) ---

        // 1. Visitor Chart (Marketing & Admin)
        if ($user->can('view-analytics-dashboard') || $user->hasRole('Super Admin')) {
            $largeWidgets[] = (new VisitorChart)->build();
        }

        // 2. Schedule Incoming List (General User)
        if ($user->can('view-schedules') || $user->hasRole('Super Admin')) {
            $largeWidgets[] = (new ScheduleIncoming)->build();
        }

        // 3. Visitor Activity Log (Marketing & Admin)
        if ($user->can('view-analytics-dashboard') || $user->hasRole('Super Admin')) {
            $largeWidgets[] = (new VisitorActivity)->build();
        }

        // 4. System Health Chart (IT / Super Admin)
        if ($user->can('view-system-dashboard') || $user->hasRole('Super Admin')) {
            $largeWidgets[] = (new SystemHealth)->build();
        }

        // Urutkan widget berdasarkan 'order'
        usort($smallWidgets, fn($a, $b) => $a['order'] <=> $b['order']);
        usort($largeWidgets, fn($a, $b) => $a['order'] <=> $b['order']);

        return Inertia::render('dashboard', [
            'smallWidgets' => $smallWidgets,
            'largeWidgets' => $largeWidgets,
            'userPermissions' => $user->getAllPermissions()->pluck('name'),
        ]);
    }
}
