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
        $filterType = $request->input('filterType', 'day');
        $chartType = $request->input('chartType', 'gioBanChay'); // Lấy giá trị mặc định là giờ bán chạy
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

        // Tính tổng doanh thu
        $totalSales = $query->sum('tong_tien');

        // Lấy dữ liệu giờ bán chạy hoặc bán ít
        $topDoanhThuQuery = $query->select(
            DB::raw("DATE_FORMAT(created_at, '%H:%i') as hour"),
            DB::raw("SUM(tong_tien) as total_revenue")
        )
            ->groupBy('hour');

        if ($chartType == 'gioBanChay') {
            $topDoanhThuQuery->orderByDesc('total_revenue'); // Sắp xếp giảm dần
        } else {
            $topDoanhThuQuery->orderBy('total_revenue'); // Sắp xếp tăng dần
        }

        $topDoanhThu = $topDoanhThuQuery->limit(6)->get();

        // Gán dữ liệu cho biểu đồ
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
