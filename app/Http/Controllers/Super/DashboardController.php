<?php

namespace App\Http\Controllers\Super;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\SystemActivityLog;
use App\Models\FinanceRecord;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        // Mengambil statistik ringkas untuk widget dashboard
        $stats = [
            'total_users' => User::count(),
            'active_users' => User::where('status', 'active')->count(),
            'system_errors' => SystemActivityLog::where('severity', 'critical')->whereDate('created_at', today())->count(),
            // Contoh statistik finance jika ada data
            'total_revenue' => FinanceRecord::where('transaction_type', 'income')->sum('amount'),
        ];

        // Mengambil log aktivitas terbaru untuk dipantau
        $recentLogs = SystemActivityLog::with('causer')
            ->latest()
            ->take(5)
            ->get();

        return Inertia::render('Super/Dashboard', [
            'stats' => $stats,
            'recentLogs' => $recentLogs
        ]);
    }
}
