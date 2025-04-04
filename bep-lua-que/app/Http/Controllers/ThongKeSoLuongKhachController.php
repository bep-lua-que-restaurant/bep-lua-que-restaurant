<?php

namespace App\Http\Controllers;

use App\Models\DatBan;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ThongKeSoLuongKhachController extends Controller
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

            if ($diffDays >= 365) {
                $filterType = 'year';
            } elseif ($diffDays >= 30) {
                $filterType = 'month';
            } else {
                $filterType = 'day';
            }

            if ($filterType === 'day') {
                $labels = [];
                for ($date = $from->copy(); $date->lte($to); $date->addDay()) {
                    $labels[] = $date->format('d/m/Y');
                }
                $rawData = DatBan::selectRaw('DATE(thoi_gian_den) as date, SUM(so_nguoi) as total_customers')
                    ->whereBetween('thoi_gian_den', [$from, $to])
                    ->where('trang_thai', 'da_thanh_toan') //Chỉ tính trạng thái "da_thanh_toan"
                    ->groupBy('date')
                    ->orderBy('date')
                    ->pluck('total_customers', 'date')
                    ->toArray();
                foreach ($labels as $label) {
                    $data[] = $rawData[Carbon::createFromFormat('d/m/Y', $label)->toDateString()] ?? 0;
                }
            } elseif ($filterType === 'month') {
                $labels = [];
                for ($date = $from->copy(); $date->lte($to); $date->addMonth()) {
                    $labels[] = $date->format('m/Y');
                }
                $rawData = DatBan::selectRaw('DATE_FORMAT(thoi_gian_den, "%Y-%m") as month, SUM(so_nguoi) as total_customers')
                    ->whereBetween('thoi_gian_den', [$from, $to])
                    ->where('trang_thai', 'da_thanh_toan') //Chỉ tính trạng thái "da_thanh_toan"
                    ->groupBy('month')
                    ->orderBy('month')
                    ->pluck('total_customers', 'month')
                    ->toArray();
                foreach ($labels as $label) {
                    $data[] = $rawData[Carbon::createFromFormat('m/Y', $label)->format('Y-m')] ?? 0;
                }
            } elseif ($filterType === 'year') {
                $labels = range($from->year, $to->year);
                $rawData = DatBan::selectRaw('YEAR(thoi_gian_den) as year, SUM(so_nguoi) as total_customers')
                    ->whereBetween('thoi_gian_den', [$from, $to])
                    ->where('trang_thai', 'da_thanh_toan') //Chỉ tính trạng thái "da_thanh_toan"
                    ->groupBy('year')
                    ->orderBy('year')
                    ->pluck('total_customers', 'year')
                    ->toArray();
                foreach ($labels as $label) {
                    $data[] = $rawData[$label] ?? 0;
                }
            }
        } else {
            if ($filterType == 'year') {
                $year = Carbon::now()->year;
                $labels = array_map(fn($m) => "Tháng $m", range(1, 12));
                $rawData = DatBan::selectRaw('MONTH(thoi_gian_den) as month, SUM(so_nguoi) as total_customers')
                    ->whereYear('thoi_gian_den', $year)
                    ->where('trang_thai', 'da_thanh_toan') //Chỉ tính trạng thái "da_thanh_toan"
                    ->groupBy('month')
                    ->orderBy('month')
                    ->pluck('total_customers', 'month')
                    ->toArray();
                foreach (range(1, 12) as $month) {
                    $data[] = $rawData[$month] ?? 0;
                }
            } elseif ($filterType == 'month') {
                $year = Carbon::now()->year;
                $month = Carbon::now()->month;
                $daysInMonth = Carbon::now()->daysInMonth;
                $labels = array_map(fn($d) => "Ngày $d", range(1, $daysInMonth));
                $rawData = DatBan::selectRaw('DAY(thoi_gian_den) as day, SUM(so_nguoi) as total_customers')
                    ->whereYear('thoi_gian_den', $year)
                    ->whereMonth('thoi_gian_den', $month)
                    ->where('trang_thai', 'da_thanh_toan') //Chỉ tính trạng thái "da_thanh_toan"
                    ->groupBy('day')
                    ->orderBy('day')
                    ->pluck('total_customers', 'day')
                    ->toArray();
                foreach (range(1, $daysInMonth) as $day) {
                    $data[] = $rawData[$day] ?? 0;
                }
            } elseif ($filterType == 'week') {
                $startOfWeek = Carbon::now()->startOfWeek();
                $endOfWeek = Carbon::now()->endOfWeek();
                $labels = [];
                for ($date = $startOfWeek->copy(); $date->lte($endOfWeek); $date->addDay()) {
                    $labels[] = "Ngày " . $date->format('d/m');
                }
                $rawData = DatBan::selectRaw('DATE(thoi_gian_den) as date, SUM(so_nguoi) as total_customers')
                    ->whereBetween('thoi_gian_den', [$startOfWeek, $endOfWeek])
                    ->where('trang_thai', 'da_thanh_toan') //Chỉ tính trạng thái "da_thanh_toan"
                    ->groupBy('date')
                    ->orderBy('date')
                    ->pluck('total_customers', 'date')
                    ->toArray();
                foreach (range(0, 6) as $i) {
                    $data[] = $rawData[$startOfWeek->copy()->addDays($i)->toDateString()] ?? 0;
                }
            } elseif ($filterType == 'day') {
                $date = Carbon::now()->toDateString();
                $labels = array_map(fn($h) => "$h:00", range(0, 23));
                $rawData = DatBan::selectRaw('HOUR(thoi_gian_den) as hour, SUM(so_nguoi) as total_customers')
                    ->whereDate('thoi_gian_den', $date)
                    ->where('trang_thai', 'da_thanh_toan') //Chỉ tính trạng thái "da_thanh_toan"
                    ->groupBy('hour')
                    ->orderBy('hour')
                    ->pluck('total_customers', 'hour')
                    ->toArray();
                foreach (range(0, 23) as $hour) {
                    $data[] = $rawData[$hour] ?? 0;
                }
            }
        }

        if ($request->ajax()) {
            return response()->json([
                'labels' => $labels,
                'data' => $data,
                'filterType' => $filterType,
                'totalCustomers' => number_format(array_sum($data), 0, ',', '.') . ' khách',
            ]);
        }

        return view('admin.thongke.thongkesoluongkhach', compact('labels', 'data', 'filterType'));
    }
}
