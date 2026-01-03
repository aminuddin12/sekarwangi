<?php

namespace App\Widgets;

use App\Models\Order; // Pastikan model Order ada
use App\Models\FinanceRecord; // Atau FinanceRecord jika menggunakan modul finance

class SummaryCount
{
    public function build(): array
    {
        // Menghitung laba bersih (misal: Total Order yang 'completed')
        $revenue = Order::where('status', 'completed')->sum('total_amount');

        // Perbandingan dengan bulan lalu (opsional)
        $lastMonth = Order::where('status', 'completed')
            ->whereMonth('created_at', now()->subMonth()->month)
            ->sum('total_amount');

        $growth = $lastMonth > 0 ? (($revenue - $lastMonth) / $lastMonth) * 100 : 0;
        $growthStr = $growth >= 0 ? "+" . number_format($growth, 1) . "%" : number_format($growth, 1) . "%";

        return [
            'id' => 'revenue_summary',
            'type' => 'StatsCard',
            'order' => 2,
            'props' => [
                'title' => 'Laba Bersih',
                'value' => 'Rp ' . number_format($revenue, 0, ',', '.'),
                'icon' => 'wallet',
                'color' => 'emerald',
                'description' => "{$growthStr} dari bulan lalu",
            ]
        ];
    }
}
