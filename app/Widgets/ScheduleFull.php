<?php

namespace App\Widgets;

use App\Models\Schedule;

class ScheduleFull
{
    public function build(): array
    {
        // Menampilkan data bulan ini
        $count = Schedule::whereMonth('start_time', now()->month)
            ->whereYear('start_time', now()->year)
            ->count();

        return [
            'id' => 'monthly_schedule_count',
            'type' => 'StatsCard',
            'order' => 7,
            'props' => [
                'title' => 'Agenda Bulan Ini',
                'value' => $count . ' Kegiatan',
                'icon' => 'calendar', // Pastikan icon ini ada di mapping StatsCard frontend
                'color' => 'blue',
                'description' => 'Total agenda terjadwal bulan ' . now()->format('F'),
            ]
        ];
    }
}
