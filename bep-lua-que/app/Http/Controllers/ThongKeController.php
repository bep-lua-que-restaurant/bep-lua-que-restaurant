<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ThongKeController extends Controller
{
    public function index(Request $request)
    {
        $now = Carbon::now();

        // Lấy dữ liệu thống kê theo ngày
        $dataNgay = DB::table('hoa_dons')
            ->select(DB::raw('DATE(created_at) as label'), DB::raw('SUM(tong_tien) as total'))
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('label', 'asc')
            ->get();

        // Lấy dữ liệu thống kê theo giờ
        $dataGio = DB::table('hoa_dons')
            ->select(DB::raw('HOUR(created_at) as label'), DB::raw('SUM(tong_tien) as total'))
            ->groupBy(DB::raw('HOUR(created_at)'))
            ->orderBy('label', 'asc')
            ->get();

        // Lấy dữ liệu thống kê theo thứ trong tuần
        $dataThu = DB::table('hoa_dons')
            ->select(DB::raw('DAYOFWEEK(created_at) as label'), DB::raw('SUM(tong_tien) as total'))
            ->groupBy(DB::raw('DAYOFWEEK(created_at)'))
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

        return view('admin.dashboard', compact('dataNgay', 'dataGio', 'dataThu'));
    }
}


