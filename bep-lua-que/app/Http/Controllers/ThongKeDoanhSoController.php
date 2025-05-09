<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\HoaDon;

class ThongKeDoanhSoController extends Controller
{
    public function index(Request $request)
    {
        $boLoc = $request->input('boLoc', 'day');
        $ngayBatDau = $request->input('ngayBatDau');
        $ngayKetThuc = $request->input('ngayKetThuc');
        $data = [];
        $labels = [];

        if ($ngayBatDau && $ngayKetThuc) {
            $tu = Carbon::parse($ngayBatDau)->startOfDay();
            $den = Carbon::parse($ngayKetThuc)->endOfDay();
            $dieuKienNgay = $tu->diffInDays($den);

            if ($dieuKienNgay >= 365) {
                $boLoc = 'year';
            } elseif ($dieuKienNgay >= 30) {
                $boLoc = 'month';
            } else {
                $boLoc = 'day';
            }

            if ($boLoc === 'day') {
                $labels = [];
                for ($date = $tu->copy(); $date->lte($den); $date->addDay()) {
                    $labels[] = $date->format('d/m/Y');
                }
                $duLieu = HoaDon::selectRaw('DATE(created_at) as date, SUM(tong_tien) as revenue')
                    ->whereBetween('created_at', [$tu, $den])
                    ->whereHas('hoaDonBan', function ($query) {
                        $query->where('trang_thai', 'da_thanh_toan');
                    })
                    ->groupBy('date')
                    ->orderBy('date')
                    ->pluck('revenue', 'date')
                    ->toArray();
                foreach ($labels as $label) {
                    $data[] = $duLieu[Carbon::createFromFormat('d/m/Y', $label)->toDateString()] ?? 0;
                }
            } elseif ($boLoc === 'month') {
                $labels = [];
                for ($date = $tu->copy(); $date->lte($den); $date->addMonth()) {
                    $labels[] = $date->format('m/Y');
                }
                $duLieu = HoaDon::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, SUM(tong_tien) as revenue')
                    ->whereBetween('created_at', [$tu, $den])
                    ->whereHas('hoaDonBan', function ($query) {
                        $query->where('trang_thai', 'da_thanh_toan');
                    })
                    ->groupBy('month')
                    ->orderBy('month')
                    ->pluck('revenue', 'month')
                    ->toArray();
                foreach ($labels as $label) {
                    $data[] = $duLieu[Carbon::createFromFormat('m/Y', $label)->format('Y-m')] ?? 0;
                }
            } elseif ($boLoc === 'year') {
                $labels = range($tu->year, $den->year);
                $duLieu = HoaDon::selectRaw('YEAR(created_at) as year, SUM(tong_tien) as revenue')
                    ->whereBetween('created_at', [$tu, $den])
                    ->whereHas('hoaDonBan', function ($query) {
                        $query->where('trang_thai', 'da_thanh_toan');
                    })
                    ->groupBy('year')
                    ->orderBy('year')
                    ->pluck('revenue', 'year')
                    ->toArray();
                foreach ($labels as $label) {
                    $data[] = $duLieu[$label] ?? 0;
                }
            }
        } else {
            if ($boLoc == 'year') {
                $year = Carbon::now()->year;
                $labels = array_map(fn($m) => "Tháng $m", range(1, 12));
                $duLieu = HoaDon::selectRaw('MONTH(created_at) as month, SUM(tong_tien) as revenue')
                    ->whereYear('created_at', $year)
                    ->whereHas('hoaDonBan', function ($query) {
                        $query->where('trang_thai', 'da_thanh_toan');
                    })
                    ->groupBy('month')
                    ->orderBy('month')
                    ->pluck('revenue', 'month')
                    ->toArray();
                foreach (range(1, 12) as $month) {
                    $data[] = $duLieu[$month] ?? 0;
                }
            } elseif ($boLoc == 'month') {
                $year = Carbon::now()->year;
                $month = Carbon::now()->month;
                $daysInMonth = Carbon::now()->daysInMonth;
                $labels = array_map(fn($d) => "Ngày $d", range(1, $daysInMonth));
                $duLieu = HoaDon::selectRaw('DAY(created_at) as day, SUM(tong_tien) as revenue')
                    ->whereYear('created_at', $year)
                    ->whereMonth('created_at', $month)
                    ->whereHas('hoaDonBan', function ($query) {
                        $query->where('trang_thai', 'da_thanh_toan');
                    })
                    ->groupBy('day')
                    ->orderBy('day')
                    ->pluck('revenue', 'day')
                    ->toArray();
                foreach (range(1, $daysInMonth) as $day) {
                    $data[] = $duLieu[$day] ?? 0;
                }
            } elseif ($boLoc == 'week') {
                $startOfWeek = Carbon::now()->startOfWeek();
                $endOfWeek = Carbon::now()->endOfWeek();
                $labels = [];
                for ($date = $startOfWeek->copy(); $date->lte($endOfWeek); $date->addDay()) {
                    $labels[] = "Ngày " . $date->format('d/m');
                }
                $duLieu = HoaDon::selectRaw('DATE(created_at) as date, SUM(tong_tien) as revenue')
                    ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
                    ->whereHas('hoaDonBan', function ($query) {
                        $query->where('trang_thai', 'da_thanh_toan');
                    })
                    ->groupBy('date')
                    ->orderBy('date')
                    ->pluck('revenue', 'date')
                    ->toArray();
                foreach (range(0, 6) as $i) {
                    $data[] = $duLieu[$startOfWeek->copy()->addDays($i)->toDateString()] ?? 0;
                }
            } elseif ($boLoc == 'day') {
                $date = Carbon::now()->toDateString();
                $labels = array_map(fn($h) => "$h:00", range(0, 23));
                $duLieu = HoaDon::selectRaw('HOUR(created_at) as hour, SUM(tong_tien) as revenue')
                    ->whereDate('created_at', $date)
                    ->whereHas('hoaDonBan', function ($query) {
                        $query->where('trang_thai', 'da_thanh_toan');
                    })
                    ->groupBy('hour')
                    ->orderBy('hour')
                    ->pluck('revenue', 'hour')
                    ->toArray();
                foreach (range(0, 23) as $hour) {
                    $data[] = $duLieu[$hour] ?? 0;
                }
            }
        }

        if ($request->ajax()) {
            return response()->json([
                'labels' => $labels,
                'data' => $data,
                'boLoc' => $boLoc,
                'tongDoanhSo' => number_format(array_sum($data), 0, ',', '.') . ' VND',
            ]);
        }

        return view('admin.thongke.thongkedoanhso', compact('labels', 'data', 'boLoc'));
    }
}
