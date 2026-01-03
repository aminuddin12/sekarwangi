<?php

namespace App\Widgets;

use App\Models\Schedule;
use Illuminate\Support\Facades\Auth;

class ScheduleIncoming
{
    public function build(): array
    {
        $user = Auth::user();

        // Logika sederhana: Ambil jadwal mendatang
        // Bisa difilter berdasarkan role user atau divisi jika perlu
        $schedules = Schedule::where('start_time', '>=', now())
            ->orderBy('start_time', 'asc')
            ->take(3)
            ->get();

        // Reuse komponen logs untuk menampilkan list jadwal
        $formattedSchedules = $schedules->map(function ($sched) {
            return [
                'id' => $sched->id,
                'action' => $sched->title ?? 'Agenda Kegiatan',
                'description' => $sched->description ?? 'Tidak ada detail',
                'user' => 'System', // Atau nama pembuat jadwal
                'time' => $sched->start_time->format('d M Y H:i'),
                'severity' => 'warning', // Kuning untuk jadwal
            ];
        });

        return [
            'id' => 'incoming_schedule',
            'type' => 'RecentLogs', // Reuse komponen logs
            'order' => 6,
            'props' => [
                'title' => 'Jadwal Mendatang',
                'logs' => $formattedSchedules
            ]
        ];
    }
}
