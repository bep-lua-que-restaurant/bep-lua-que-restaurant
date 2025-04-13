<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\HoaDon;

class ThongKeDoanhSoController extends Controller
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
                $rawData = HoaDon::selectRaw('DATE(hoa_dons.created_at) as date, SUM(hoa_dons.tong_tien) as revenue')
                    ->join('hoa_don_bans', 'hoa_dons.id', '=', 'hoa_don_bans.hoa_don_id')
                    ->where('hoa_don_bans.trang_thai', 'da_thanh_toan')
                    ->whereBetween('hoa_dons.created_at', [$from, $to])
                    ->groupBy('date')
                    ->orderBy('date')
                    ->pluck('revenue', 'date')
                    ->toArray();
                foreach ($labels as $label) {
                    $data[] = $rawData[Carbon::createFromFormat('d/m/Y', $label)->toDateString()] ?? 0;
                }
            } elseif ($filterType === 'month') {
                $labels = [];
                for ($date = $from->copy(); $date->lte($to); $date->addMonth()) {
                    $labels[] = $date->format('m/Y');
                }
                $rawData = HoaDon::selectRaw('DATE_FORMAT(hoa_dons.created_at, "%Y-%m") as month, SUM(hoa_dons.tong_tien) as revenue')
                    ->join('hoa_don_bans', 'hoa_dons.id', '=', 'hoa_don_bans.hoa_don_id')
                    ->where('hoa_don_bans.trang_thai', 'da_thanh_toan')
                    ->whereBetween('hoa_dons.created_at', [$from, $to])
                    ->groupBy('month')
                    ->orderBy('month')
                    ->pluck('revenue', 'month')
                    ->toArray();
                foreach ($labels as $label) {
                    $data[] = $rawData[Carbon::createFromFormat('m/Y', $label)->format('Y-m')] ?? 0;
                }
            } elseif ($filterType === 'year') {
                $labels = range($from->year, $to->year);
                $rawData = HoaDon::selectRaw('YEAR(hoa_dons.created_at) as year, SUM(hoa_dons.tong_tien) as revenue')
                    ->join('hoa_don_bans', 'hoa_dons.id', '=', 'hoa_don_bans.hoa_don_id')
                    ->where('hoa_don_bans.trang_thai', 'da_thanh_toan')
                    ->whereBetween('hoa_dons.created_at', [$from, $to])
                    ->groupBy('year')
                    ->orderBy('year')
                    ->pluck('revenue', 'year')
                    ->toArray();
                foreach ($labels as $label) {
                    $data[] = $rawData[$label] ?? 0;
                }
            }
        } else {
            if ($filterType == 'year') {
                $year = Carbon::now()->year;
                $labels = array_map(fn($m) => "Tháng $m", range(1, 12));
                $rawData = HoaDon::selectRaw('MONTH(hoa_dons.created_at) as month, SUM(hoa_dons.tong_tien) as revenue')
                    ->join('hoa_don_bans', 'hoa_dons.id', '=', 'hoa_don_bans.hoa_don_id')
                    ->where('hoa_don_bans.trang_thai', 'da_thanh_toan')
                    ->whereYear('hoa_dons.created_at', $year)
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
                $rawData = HoaDon::selectRaw('DAY(hoa_dons.created_at) as day, SUM(hoa_dons.tong_tien) as revenue')
                    ->join('hoa_don_bans', 'hoa_dons.id', '=', 'hoa_don_bans.hoa_don_id')
                    ->where('hoa_don_bans.trang_thai', 'da_thanh_toan')
                    ->whereYear('hoa_dons.created_at', $year)
                    ->whereMonth('hoa_dons.created_at', $month)
                    ->groupBy('day')
                    ->orderBy('day')
                    ->pluck('revenue', 'day')
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
                $rawData = HoaDon::selectRaw('DATE(hoa_dons.created_at) as date, SUM(hoa_dons.tong_tien) as revenue')
                    ->join('hoa_don_bans', 'hoa_dons.id', '=', 'hoa_don_bans.hoa_don_id')
                    ->where('hoa_don_bans.trang_thai', 'da_thanh_toan')
                    ->whereBetween('hoa_dons.created_at', [$startOfWeek, $endOfWeek])
                    ->groupBy('date')
                    ->orderBy('date')
                    ->pluck('revenue', 'date')
                    ->toArray();
                foreach (range(0, 6) as $i) {
                    $data[] = $rawData[$startOfWeek->copy()->addDays($i)->toDateString()] ?? 0;
                }
            } elseif ($filterType == 'day') {
                $date = Carbon::now()->toDateString();
                $labels = array_map(fn($h) => "$h:00", range(0, 23));
                $rawData = HoaDon::selectRaw('HOUR(hoa_dons.created_at) as hour, SUM(hoa_dons.tong_tien) as revenue')
                    ->join('hoa_don_bans', 'hoa_dons.id', '=', 'hoa_don_bans.hoa_don_id')
                    ->where('hoa_don_bans.trang_thai', 'da_thanh_toan')
                    ->whereDate('hoa_dons.created_at', $date)
                    ->groupBy('hour')
                    ->orderBy('hour')
                    ->pluck('revenue', 'hour')
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
                'totalSales' => number_format(array_sum($data), 0, ',', '.') . ' VND',
            ]);
        }

        return view('admin.thongke.thongkedoanhso', compact('labels', 'data', 'filterType'));
    }
}
