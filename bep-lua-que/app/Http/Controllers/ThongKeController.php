<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\HoaDon;

class ThongKeController extends Controller
{
    public function index(Request $request)
    {
        $filterType = $request->input('filterType', 'day');
        $data = [];
        $labels = [];

        // Thống kê theo năm, tháng, ngày
        if ($filterType == 'year') {
            $year = Carbon::now()->year;
            $labels = array_map(fn($m) => "Tháng $m", range(1, 12));
            $rawData = HoaDon::selectRaw('MONTH(created_at) as month, SUM(tong_tien) as revenue')
                ->whereYear('created_at', $year)
                ->groupBy('month')
                ->orderBy('month')
                ->pluck('revenue', 'month')
                ->toArray();

            foreach (range(1, 12) as $month) {
                $data[] = $rawData[$month] ?? 0;
            }
        } elseif ($filterType == 'month') {
            $year = Carbon::now()->year;
            $month = Carbon::now()->month;
            $daysInMonth = Carbon::now()->daysInMonth;
            $labels = array_map(fn($d) => "Ngày $d", range(1, $daysInMonth));
            $rawData = HoaDon::selectRaw('DAY(created_at) as day, SUM(tong_tien) as revenue')
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->groupBy('day')
                ->orderBy('day')
                ->pluck('revenue', 'day')
                ->toArray();

            foreach (range(1, $daysInMonth) as $day) {
                $data[] = $rawData[$day] ?? 0;
            }
        } elseif ($filterType == 'day') {
            $date = Carbon::now()->toDateString();
            $labels = array_map(fn($h) => "$h:00", range(0, 23));
            $rawData = HoaDon::selectRaw('HOUR(created_at) as hour, SUM(tong_tien) as revenue')
                ->whereDate('created_at', $date)
                ->groupBy('hour')
                ->orderBy('hour')
                ->pluck('revenue', 'hour')
                ->toArray();

            foreach (range(0, 23) as $hour) {
                $data[] = $rawData[$hour] ?? 0;
            }
        }

        // **Thống kê doanh số tháng này vs tháng trước**
        $currentMonthRevenue = HoaDon::whereYear('created_at', Carbon::now()->year)
            ->whereMonth('created_at', Carbon::now()->month)
            ->sum('tong_tien');

        $lastMonthRevenue = HoaDon::whereYear('created_at', Carbon::now()->year)
            ->whereMonth('created_at', Carbon::now()->subMonth()->month)
            ->sum('tong_tien');

        // **Thống kê doanh số tuần này vs tuần trước**
        $currentWeekRevenue = HoaDon::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->sum('tong_tien');

        $lastWeekRevenue = HoaDon::whereBetween('created_at', [Carbon::now()->subWeek()->startOfWeek(), Carbon::now()->subWeek()->endOfWeek()])
            ->sum('tong_tien');

        // Tính phần trăm chênh lệch
        $monthPercentage = $lastMonthRevenue > 0
            ? (($currentMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100
            : 0;

        $weekPercentage = $lastWeekRevenue > 0
            ? (($currentWeekRevenue - $lastWeekRevenue) / $lastWeekRevenue) * 100
            : 0;

        if ($request->ajax()) {
            return response()->json([
                'labels' => $labels,
                'data' => $data,
                'totalSales' => number_format(array_sum($data), 0, ',', '.') . ' VND',
                'monthComparison' => [
                    'currentMonth' => $currentMonthRevenue,
                    'lastMonth' => $lastMonthRevenue,
                    'percentage' => round($monthPercentage, 2)
                ],
                'weekComparison' => [
                    'currentWeek' => $currentWeekRevenue,
                    'lastWeek' => $lastWeekRevenue,
                    'percentage' => round($weekPercentage, 2)
                ]
            ]);
        }

        return view('admin.dashboard', compact('labels', 'data', 'filterType', 'currentMonthRevenue', 'lastMonthRevenue', 'currentWeekRevenue', 'lastWeekRevenue', 'monthPercentage', 'weekPercentage'));
    }

}
