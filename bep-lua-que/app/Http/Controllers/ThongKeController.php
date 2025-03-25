<?php

namespace App\Http\Controllers;

use App\Models\DatBan;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\HoaDon;
use App\Models\HoaDonBan;

class ThongKeController extends Controller
{
    public function index(Request $request)
    {
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();

        // Truy vấn doanh thu chỉ với hóa đơn có trạng thái 'da_thanh_toan'
        $revenueData = HoaDon::join('hoa_don_bans', 'hoa_dons.id', '=', 'hoa_don_bans.hoa_don_id')
            ->where('hoa_don_bans.trang_thai', 'da_thanh_toan') // Chỉ lấy hóa đơn đã thanh toán
            ->whereDate('hoa_dons.created_at', '>=', $yesterday)
            ->selectRaw('DATE(hoa_dons.created_at) as date, SUM(hoa_dons.tong_tien) as revenue')
            ->groupBy('date')
            ->pluck('revenue', 'date');

        $totalRevenueToday = $revenueData[$today->toDateString()] ?? 0;
        $totalRevenueYesterday = $revenueData[$yesterday->toDateString()] ?? 0;

        // Số đơn đang phục vụ hôm nay (trạng thái 'dang_xu_ly')
        $ordersServingToday = HoaDonBan::whereDate('created_at', $today)
            ->where('trang_thai', 'dang_xu_ly')
            ->count();

        // Số đơn đã phục vụ hôm qua (trạng thái 'da_thanh_toan')
        $ordersCompletedYesterday = HoaDonBan::whereDate('created_at', $yesterday)
            ->where('trang_thai', 'da_thanh_toan')
            ->count();

        // Truy vấn số khách hôm nay & hôm qua
        $customerData = DatBan::whereDate('created_at', '>=', $yesterday)
            ->where('trang_thai', 'xac_nhan')

            ->selectRaw('DATE(thoi_gian_den) as date, SUM(so_nguoi) as total_customers')

            ->selectRaw('DATE(created_at) as date, SUM(so_nguoi) as total_customers')

            ->groupBy('date')
            ->pluck('total_customers', 'date');

        $customersToday = $customerData[$today->toDateString()] ?? 0;
        $customersYesterday = $customerData[$yesterday->toDateString()] ?? 0;

        // Truy vấn doanh số theo giờ trong ngày hôm nay (chỉ lấy hóa đơn đã thanh toán)
        $salesData = HoaDon::join('hoa_don_bans', 'hoa_dons.id', '=', 'hoa_don_bans.hoa_don_id')
            ->where('hoa_don_bans.trang_thai', 'da_thanh_toan')
            ->whereDate('hoa_dons.created_at', $today)
            ->selectRaw('HOUR(hoa_dons.created_at) as hour, SUM(hoa_dons.tong_tien) as revenue')
            ->groupBy('hour')
            ->pluck('revenue', 'hour');

        $labels = array_map(fn($h) => "$h:00", range(0, 23));
        $data = array_map(fn($h) => $salesData[$h] ?? 0, range(0, 23));

        if ($request->ajax()) {
            return response()->json([
                'labels' => $labels,
                'data' => $data,
                'totalSales' => number_format($totalRevenueToday, 0, ',', '.') . ' VND',
                'totalRevenueToday' => number_format($totalRevenueToday, 0, ',', '.') . ' VND',
                'totalRevenueYesterday' => number_format($totalRevenueYesterday, 0, ',', '.') . ' VND',
                'customersToday' => $customersToday,
                'customersYesterday' => $customersYesterday,
                'ordersServingToday' => $ordersServingToday,
                'ordersCompletedYesterday' => $ordersCompletedYesterday
            ]);
        }

        return view('admin.dashboard', compact(
            'labels', 'data', 'totalRevenueToday', 'totalRevenueYesterday',
            'ordersServingToday', 'ordersCompletedYesterday',
            'customersToday', 'customersYesterday'
        ));
    }
}
