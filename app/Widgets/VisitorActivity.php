<?php

namespace App\Widgets;

use App\Models\VisitorLog;

class VisitorActivity
{
    public function build(): array
    {
        $activities = VisitorLog::latest()
            ->take(5)
            ->get()
            ->map(function ($log) {
                return [
                    'id' => $log->id,
                    'action' => 'Kunjungan Halaman',
                    'description' => ($log->url ?? 'Halaman Utama') . " via " . ($log->device ?? 'Unknown Device'),
                    'user' => $log->ip_address,
                    'time' => $log->created_at->diffForHumans(),
                    'severity' => 'info',
                ];
            });

        return [
            'id' => 'visitor_activity',
            'type' => 'RecentLogs',
            'order' => 5,
            'props' => [
                'title' => 'Aktivitas Pengunjung Terbaru',
                'logs' => $activities
            ]
        ];
    }
}
