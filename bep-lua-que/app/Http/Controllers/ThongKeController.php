<?php

namespace App\Http\Controllers;

use App\Models\DatBan;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\HoaDon;
use App\Models\HoaDonBan;
use Illuminate\Support\Facades\Auth;

class ThongKeController extends Controller
{
    public function index(Request $request)
    {

        if (!Auth::check()) {
            return redirect('/login');
        }

        $homNay = Carbon::today();
        $homQua = Carbon::yesterday();

        // Truy vấn doanh thu chỉ với hóa đơn có trạng thái 'da_thanh_toan'
        $duLieuDoanhThu = HoaDon::join('hoa_don_bans', 'hoa_dons.id', '=', 'hoa_don_bans.hoa_don_id')
            ->where('hoa_don_bans.trang_thai', 'da_thanh_toan') // Chỉ lấy hóa đơn đã thanh toán
            ->whereDate('hoa_dons.created_at', '>=', $homQua)
            ->selectRaw('DATE(hoa_dons.created_at) as date, SUM(hoa_dons.tong_tien) as revenue')
            ->groupBy('date')
            ->pluck('revenue', 'date');

        $tongTienHomNay = $duLieuDoanhThu[$homNay->toDateString()] ?? 0;
        $tongTienHomQua = $duLieuDoanhThu[$homQua->toDateString()] ?? 0;

        // Số đơn đang phục vụ hôm nay (trạng thái 'dang_xu_ly')
        $donDangPhucVuHomNay = HoaDonBan::whereDate('created_at', $homNay)
            ->where('trang_thai', 'dang_xu_ly')
            ->count();

        // Số đơn đã phục vụ hôm qua (trạng thái 'da_thanh_toan')
        $donPhucVuHomQua = HoaDonBan::whereDate('created_at', $homQua)
            ->where('trang_thai', 'da_thanh_toan')
            ->count();

        // Truy vấn số khách hôm nay & hôm qua
        $duLieuKhachHang = DatBan::whereDate('created_at', '>=', $homQua)
            ->where('trang_thai', 'da_thanh_toan')
            ->selectRaw('DATE(thoi_gian_den) as date, SUM(so_nguoi) as total_customers')
            ->groupBy('date')
            ->pluck('total_customers', 'date');

        $soLuongKhachHomNay = $duLieuKhachHang[$homNay->toDateString()] ?? 0;
        $soLuongKhachHomQua = $duLieuKhachHang[$homQua->toDateString()] ?? 0;

        // Truy vấn doanh số theo giờ trong ngày hôm nay (chỉ lấy hóa đơn đã thanh toán)
        $duLieuBanHang = HoaDon::join('hoa_don_bans', 'hoa_dons.id', '=', 'hoa_don_bans.hoa_don_id')
            ->where('hoa_don_bans.trang_thai', 'da_thanh_toan')
            ->whereDate('hoa_dons.created_at', $homNay)
            ->selectRaw('HOUR(hoa_dons.created_at) as hour, SUM(hoa_dons.tong_tien) as revenue')
            ->groupBy('hour')
            ->pluck('revenue', 'hour');

        $labels = array_map(fn($h) => "$h:00", range(0, 23));
        $data = array_map(fn($h) => $duLieuBanHang[$h] ?? 0, range(0, 23));

        if ($request->ajax()) {
            return response()->json([
                'labels' => $labels,
                'data' => $data,
                'tongDoanhSo' => number_format($tongTienHomNay, 0, ',', '.') . ' VND',
                'tongTienHomNay' => number_format($tongTienHomNay, 0, ',', '.') . ' VND',
                'tongTienHomQua' => number_format($tongTienHomQua, 0, ',', '.') . ' VND',
                'soLuongKhachHomNay' => $soLuongKhachHomNay,
                'soLuongKhachHomQua' => $soLuongKhachHomQua,
                'donDangPhucVuHomNay' => $donDangPhucVuHomNay,
                'donPhucVuHomQua' => $donPhucVuHomQua
            ]);
        }

        return view('admin.dashboard', compact(
            'labels', 'data', 'tongTienHomNay', 'tongTienHomQua',
            'donDangPhucVuHomNay', 'donPhucVuHomQua',
            'soLuongKhachHomNay', 'soLuongKhachHomQua'
        ));
    }
}
