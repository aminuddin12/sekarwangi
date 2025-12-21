<?php

namespace App\Http\Controllers\Super;

use App\Http\Controllers\Controller;
use App\Models\SystemActivityLog;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SystemLogsController extends Controller
{
    public function index(Request $request)
    {
        $query = SystemActivityLog::with('causer')->latest();

        // Fitur Filter
        if ($request->has('severity')) {
            $query->where('severity', $request->severity);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where('description', 'like', "%{$search}%")
                  ->orWhere('log_name', 'like', "%{$search}%");
        }

        $logs = $query->paginate(20)->withQueryString();

        return Inertia::render('Super/Logs/Index', [
            'logs' => $logs,
            'filters' => $request->only(['severity', 'search']),
        ]);
    }

    public function show($id)
    {
        $log = SystemActivityLog::with(['causer', 'subject'])->findOrFail($id);

        return Inertia::render('Super/Logs/Show', [
            'log' => $log
        ]);
    }
}
