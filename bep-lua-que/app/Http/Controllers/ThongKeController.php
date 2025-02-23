<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ThongKeController extends Controller
{
    public function index(Request $request)
    {
        $now = Carbon::now('Asia/Ho_Chi_Minh');

        // Lấy dữ liệu thống kê theo ngày
        $dataNgay = DB::table('hoa_dons')
            ->select(DB::raw("DATE(CONVERT_TZ(created_at, '+00:00', '+07:00')) as label"),
                DB::raw('SUM(tong_tien) as total'))
            ->groupBy(DB::raw("DATE(CONVERT_TZ(created_at, '+00:00', '+07:00'))"))
            ->orderBy('label', 'asc')
            ->get();


        // Lấy dữ liệu thống kê theo giờ
        $dataGio = DB::table('hoa_dons')
            ->select(
                DB::raw('DATE(created_at) as date'), // Giữ nguyên ngày từ created_at
                DB::raw('HOUR(created_at) as label'), // Giữ nguyên giờ từ created_at
                DB::raw('SUM(tong_tien) as total')
            )
            ->groupBy(DB::raw('DATE(created_at)'), DB::raw('HOUR(created_at)'))
            ->orderBy('date', 'asc')
            ->orderBy('label', 'asc')
            ->get();



        // Lấy dữ liệu thống kê theo thứ trong tuần
//        $dataThu = DB::table('hoa_dons')
//            ->select(DB::raw('DAYOFWEEK(CONVERT_TZ(created_at, ) as label'), DB::raw('SUM(tong_tien) as total'))
//            ->groupBy(DB::raw('DAYOFWEEK(created_at)'))
//            ->orderBy('label', 'asc')
//            ->get();
        $timeRange = request()->input('time_range'); // Lấy bộ lọc từ request

        $query = DB::table('hoa_dons')
            ->select(
                DB::raw("DAYOFWEEK(created_at) as label"),  // Giữ nguyên UTC
                DB::raw('SUM(tong_tien) as total'),
                DB::raw("MIN(DATE(created_at)) as min_date"),
                DB::raw("MAX(DATE(created_at)) as max_date")
            );

        // 👉 **Xử lý điều kiện lọc**
        if ($timeRange === 'today') {
            $start = now()->utc()->startOfDay();
            $end = now()->utc()->endOfDay();
        } elseif ($timeRange === 'yesterday') {
            $start = now()->subDay()->utc()->startOfDay();
            $end = now()->subDay()->utc()->endOfDay();
        } elseif ($timeRange === 'last7days') {
            $start = now()->subDays(6)->utc()->startOfDay(); // Lấy dữ liệu từ 7 ngày trước
            $end = now()->utc()->endOfDay();
        } elseif ($timeRange === 'thismonth') {
            $start = now()->startOfMonth()->utc()->startOfDay();
            $end = now()->utc()->endOfDay();
        } elseif ($timeRange === 'lastmonth') {
            $start = now()->subMonthNoOverflow()->startOfMonth()->utc()->startOfDay();
            $end = now()->subMonthNoOverflow()->endOfMonth()->utc()->endOfDay();

        } else {
            // Mặc định lấy dữ liệu tháng này nếu không có bộ lọc hợp lệ
            $start = now()->startOfMonth()->utc()->startOfDay();
            $end = now()->utc()->endOfDay();
        }

        // 👉 **Chỉ áp dụng điều kiện lọc 1 lần duy nhất**
        $query->whereBetween('created_at', [$start, $end]);

        $dataThu = $query
            ->groupBy(DB::raw("DAYOFWEEK(created_at)")) // Giữ nguyên UTC
            ->orderBy('label', 'asc')
            ->get();

        // Chuyển đổi thứ trong tuần thành tên ngày
        $labelsThu = [
            1 => 'Chủ Nhật',
            2 => 'Thứ Hai',
            3 => 'Thứ Ba',
            4 => 'Thứ Tư',
            5 => 'Thứ Năm',
            6 => 'Thứ Sáu',
            7 => 'Thứ Bảy'
        ];


        foreach ($dataThu as $item) {
            $item->label = $labelsThu[$item->label] ?? 'Không xác định';
        }

        return view('admin.dashboard', [
            'dataNgay' => $dataNgay,
            'dataGio' => $dataGio,
            'dataThu' => $dataThu
        ]);
    }
}
