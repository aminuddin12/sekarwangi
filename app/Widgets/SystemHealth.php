<?php

namespace App\Widgets;

class SystemHealth
{
    public function build(): array
    {
        // Logika Monitoring Sederhana (Kompatibel Cross-Platform Basic)

        // 1. Memory Usage (PHP Process)
        $memoryUsage = memory_get_usage(true) / 1024 / 1024; // MB
        $memoryFormatted = number_format($memoryUsage, 2);

        // 2. Disk Space
        $diskTotal = disk_total_space('/');
        $diskFree = disk_free_space('/');
        $diskUsed = $diskTotal - $diskFree;
        $diskPercentage = ($diskUsed / $diskTotal) * 100;

        // 3. Load Average (Linux Only - Fallback for Windows)
        $load = function_exists('sys_getloadavg') ? sys_getloadavg()[0] : 0;

        // Kita format datanya seolah-olah chart performance
        $chartData = [
            round($load * 10), // Simulasi load ke skala 0-100 visual
            round($diskPercentage),
            round(($memoryUsage / 512) * 100) // Asumsi limit PHP 512MB
        ];

        return [
            'id' => 'system_health',
            'type' => 'RevenueChart', // Reuse Chart
            'order' => 99,
            'props' => [
                'title' => 'Kesehatan Sistem (Load, Disk, RAM %)',
                'data' => $chartData,
                'labels' => ['CPU Load', 'Disk Usage', 'RAM Usage'],
            ]
        ];
    }
}
