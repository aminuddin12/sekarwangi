<?php

namespace App\Widgets;

use App\Models\VisitorLog;

class VisitorCount
{
    public function build(): array
    {
        // Menghitung total pengunjung unik hari ini
        $todayVisitors = VisitorLog::whereDate('created_at', today())->count();
        $totalVisitors = VisitorLog::count();

        return [
            'id' => 'visitor_count',
            'type' => 'StatsCard',
            'order' => 3,
            'props' => [
                'title' => 'Pengunjung Hari Ini',
                'value' => number_format($todayVisitors),
                'icon' => 'activity',
                'color' => 'purple',
                'description' => "Total Sejarah: " . number_format($totalVisitors),
            ]
        ];
    }
}
