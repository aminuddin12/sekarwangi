<?php

namespace App\Widgets;

use App\Models\VisitorLog;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class VisitorChart
{
    public function build(string $range = 'weekly'): array
    {
        // Default: Weekly data (7 hari terakhir)
        $endDate = now();
        $startDate = now()->subDays(6);

        $visits = VisitorLog::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Format data untuk ChartJS / Recharts
        $labels = [];
        $data = [];

        // Loop untuk memastikan hari yang kosong tetap ada (nilai 0)
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $record = $visits->firstWhere('date', $date);

            $labels[] = Carbon::parse($date)->format('d M');
            $data[] = $record ? $record->count : 0;
        }

        return [
            'id' => 'visitor_chart',
            'type' => 'RevenueChart', // Kita reuse komponen Chart yang ada
            'order' => 4,
            'props' => [
                'title' => 'Tren Pengunjung (7 Hari)',
                'data' => $data,
                'labels' => $labels,
            ]
        ];
    }
}
