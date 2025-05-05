<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\HoaDon;
use Illuminate\Support\Facades\DB;

class ThongKeTopDoanhThuController extends Controller
{
    public function index(Request $request)
    {
        $filterType = $request->input('filterType', 'year');
        $chartType = $request->input('chartType', 'gioBanChay');
        $fromDate = $request->input('fromDate');
        $toDate = $request->input('toDate');

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

            $query = HoaDon::whereBetween('created_at', [$from, $to]);
        } else {
            if ($filterType == 'year') {
                $query = HoaDon::whereYear('created_at', Carbon::now()->year);
            } elseif ($filterType == 'month') {
                $query = HoaDon::whereYear('created_at', Carbon::now()->year)
                    ->whereMonth('created_at', Carbon::now()->month);
            } elseif ($filterType == 'week') {
                $query = HoaDon::whereBetween('created_at', [
                    Carbon::now()->startOfWeek(),
                    Carbon::now()->endOfWeek()
                ]);
            } elseif ($filterType == 'day') {
                $query = HoaDon::whereDate('created_at', Carbon::now()->toDateString());
            }
        }

        // Lọc theo hóa đơn đã thanh toán bằng whereHas
        $query->whereHas('hoaDonBan', function ($q) {
            $q->where('trang_thai', 'da_thanh_toan');
        });

        // Tổng doanh thu
        $totalSales = $query->sum('tong_tien');

        // Dữ liệu doanh thu theo giờ
        $topDoanhThuQuery = clone $query;

        $topDoanhThu = $topDoanhThuQuery
            ->selectRaw("DATE_FORMAT(created_at, '%H:00') as hour, SUM(tong_tien) as total_revenue")
            ->groupBy('hour')
            ->orderBy($chartType == 'gioBanChay' ? 'total_revenue' : 'total_revenue', $chartType == 'gioBanChay' ? 'desc' : 'asc')
            ->limit(5)
            ->get();

        $labels = $topDoanhThu->pluck('hour')->toArray();
        $data = $topDoanhThu->pluck('total_revenue')->toArray();

        if ($request->ajax()) {
            return response()->json([
                'labels' => $labels,
                'data' => $data,
                'totalSales' => number_format($totalSales, 0, ',', '.'),
                'filterType' => $filterType,
            ]);
        }

        return view('admin.thongke.topdoanhthu', compact('labels', 'data', 'filterType', 'totalSales', 'chartType'));
    }
}
