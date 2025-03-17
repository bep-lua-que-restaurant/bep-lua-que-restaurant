<?php

namespace App\Http\Controllers;

use App\Models\HoaDon;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ThongKeSoLuongHoaDonController extends Controller
{
    public function index(Request $request)
    {
        $filterType = $request->input('filterType', 'day');
        $fromDate = $request->input('fromDate');
        $toDate = $request->input('toDate');
        $data = [];
        $labels = [];

        if ($fromDate && $toDate) {
            $from = Carbon::parse($fromDate)->startOfDay();
            $to = Carbon::parse($toDate)->endOfDay();
            $diffDays = $from->diffInDays($to);

            // Xác định loại lọc dựa trên khoảng cách ngày
            if ($diffDays >= 365) {
                $filterType = 'year';
            } elseif ($diffDays >= 30) {
                $filterType = 'month';
            } else {
                $filterType = 'day';
            }

            // Lọc theo ngày
            if ($filterType === 'day') {
                for ($date = $from->copy(); $date->lte($to); $date->addDay()) {
                    $labels[] = $date->format('d/m/Y');
                }

                $rawData = HoaDon::selectRaw('DATE(created_at) as date, COUNT(*) as total_orders')
                    ->whereBetween('created_at', [$from, $to])
                    ->groupBy('date')
                    ->orderBy('date')
                    ->pluck('total_orders', 'date')
                    ->toArray();

                foreach ($labels as $label) {
                    $data[] = $rawData[Carbon::createFromFormat('d/m/Y', $label)->toDateString()] ?? 0;
                }
            }

            // Lọc theo tháng
            elseif ($filterType === 'month') {
                for ($date = $from->copy(); $date->lte($to); $date->addMonth()) {
                    $labels[] = $date->format('m/Y');
                }

                $rawData = HoaDon::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as total_orders')
                    ->whereBetween('created_at', [$from, $to])
                    ->groupBy('month')
                    ->orderBy('month')
                    ->pluck('total_orders', 'month')
                    ->toArray();

                foreach ($labels as $label) {
                    $data[] = $rawData[Carbon::createFromFormat('m/Y', $label)->format('Y-m')] ?? 0;
                }
            }

            // Lọc theo năm
            elseif ($filterType === 'year') {
                $labels = range($from->year, $to->year);
                $rawData = HoaDon::selectRaw('YEAR(created_at) as year, COUNT(*) as total_orders')
                    ->whereBetween('created_at', [$from, $to])
                    ->groupBy('year')
                    ->orderBy('year')
                    ->pluck('total_orders', 'year')
                    ->toArray();

                foreach ($labels as $label) {
                    $data[] = $rawData[$label] ?? 0;
                }
            }
        } else {
            // Mặc định thống kê theo hôm nay
            if ($filterType == 'day') {
                $date = Carbon::now()->toDateString();
                $labels = array_map(fn($h) => "$h:00", range(0, 23));
                $rawData = HoaDon::selectRaw('HOUR(created_at) as hour, COUNT(*) as total_orders')
                    ->whereDate('created_at', $date)
                    ->groupBy('hour')
                    ->orderBy('hour')
                    ->pluck('total_orders', 'hour')
                    ->toArray();

                foreach (range(0, 23) as $hour) {
                    $data[] = $rawData[$hour] ?? 0;
                }
            }

            // Lọc theo tuần
            elseif ($filterType == 'week') {
                $startOfWeek = Carbon::now()->startOfWeek();
                $endOfWeek = Carbon::now()->endOfWeek();
                for ($date = $startOfWeek->copy(); $date->lte($endOfWeek); $date->addDay()) {
                    $labels[] = "Ngày " . $date->format('d/m');
                }

                $rawData = HoaDon::selectRaw('DATE(created_at) as date, COUNT(*) as total_orders')
                    ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
                    ->groupBy('date')
                    ->orderBy('date')
                    ->pluck('total_orders', 'date')
                    ->toArray();

                foreach (range(0, 6) as $i) {
                    $data[] = $rawData[$startOfWeek->copy()->addDays($i)->toDateString()] ?? 0;
                }
            }

            // Lọc theo tháng
            elseif ($filterType == 'month') {
                $year = Carbon::now()->year;
                $month = Carbon::now()->month;
                $daysInMonth = Carbon::now()->daysInMonth;
                $labels = array_map(fn($d) => "Ngày $d", range(1, $daysInMonth));
                $rawData = HoaDon::selectRaw('DAY(created_at) as day, COUNT(*) as total_orders')
                    ->whereYear('created_at', $year)
                    ->whereMonth('created_at', $month)
                    ->groupBy('day')
                    ->orderBy('day')
                    ->pluck('total_orders', 'day')
                    ->toArray();

                foreach (range(1, $daysInMonth) as $day) {
                    $data[] = $rawData[$day] ?? 0;
                }
            }

            // Lọc theo năm
            elseif ($filterType == 'year') {
                $year = Carbon::now()->year;
                $labels = array_map(fn($m) => "Tháng $m", range(1, 12));
                $rawData = HoaDon::selectRaw('MONTH(created_at) as month, COUNT(*) as total_orders')
                    ->whereYear('created_at', $year)
                    ->groupBy('month')
                    ->orderBy('month')
                    ->pluck('total_orders', 'month')
                    ->toArray();

                foreach (range(1, 12) as $month) {
                    $data[] = $rawData[$month] ?? 0;
                }
            }
        }

        if ($request->ajax()) {
            return response()->json([
                'labels' => $labels,
                'data' => $data,
                'filterType' => $filterType,
                'totalOrders' => number_format(array_sum($data), 0, ',', '.') . ' hóa đơn',
            ]);
        }

        return view('admin.thongke.thongkehoadon', compact('labels', 'data', 'filterType'));
    }
}
