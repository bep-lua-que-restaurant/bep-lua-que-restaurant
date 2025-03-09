<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ThongKeMonAnController extends Controller
{
    // Thống kê tổng số lượng món ăn đã bán
    public function thongKeMonAn(Request $request)
    {
        // Lấy filterType, fromDate, và toDate từ request
        $filterType = $request->input('filterType', 'day');
        $fromDate = $request->input('fromDate');
        $toDate = $request->input('toDate');
    
        // Nếu không có fromDate hoặc toDate, gán giá trị mặc định
        if (!$fromDate || !$toDate) {
            $fromDate = Carbon::now()->subDays(7)->format('Y-m-d');
            $toDate = Carbon::now()->format('Y-m-d');
        }
    
        // Chuyển đổi ngày sang định dạng chuẩn
        $fromDate = Carbon::parse($fromDate);
        $toDate = Carbon::parse($toDate);
    
        // Điều chỉnh khoảng thời gian dựa vào filterType
        if ($filterType == 'month') {
            $fromDate = $fromDate->startOfMonth();
            $toDate = $toDate->endOfMonth();
        } elseif ($filterType == 'year') {
            $fromDate = $fromDate->startOfYear();
            $toDate = $toDate->endOfYear();
        } else {
            $fromDate = $fromDate->startOfDay();
            $toDate = $toDate->endOfDay();
        }
    
        // Truy vấn thống kê
        $query = DB::table('chi_tiet_hoa_dons')
            ->join('mon_ans', 'chi_tiet_hoa_dons.mon_an_id', '=', 'mon_ans.id')
            ->where('chi_tiet_hoa_dons.trang_thai', 'hoan_thanh')
            ->whereBetween('chi_tiet_hoa_dons.created_at', [$fromDate->startOfDay(), $toDate->endOfDay()])
            ;
    
        // Xử lý nhóm dữ liệu theo filterType
        if ($filterType == 'month') {
            $query->select(
                DB::raw('DATE_FORMAT(chi_tiet_hoa_dons.created_at, "%Y-%m") as time_label'),
                'mon_ans.ten',
                DB::raw('SUM(chi_tiet_hoa_dons.so_luong) as tong_so_luong')
            )
            ->groupBy(DB::raw('DATE_FORMAT(chi_tiet_hoa_dons.created_at, "%Y-%m")'), 'mon_ans.ten')
            ->orderByDesc('tong_so_luong');
        } elseif ($filterType == 'year') {
            $query->select(
                DB::raw('DATE(chi_tiet_hoa_dons.created_at) as time_label'),
                'mon_ans.ten',
                DB::raw('SUM(chi_tiet_hoa_dons.so_luong) as tong_so_luong')
            )
            ->groupBy(DB::raw('DATE(chi_tiet_hoa_dons.created_at)'), 'mon_ans.ten')
            ->orderByDesc('tong_so_luong');
            
        } else {
            $query->select(
                DB::raw('DATE(chi_tiet_hoa_dons.created_at) as time_label'),
                'mon_ans.ten',
                DB::raw('SUM(chi_tiet_hoa_dons.so_luong) as tong_so_luong')
            )
            ->groupBy(DB::raw('DATE(chi_tiet_hoa_dons.created_at)'), 'mon_ans.ten')
            ->orderByDesc('tong_so_luong');
            
        }
    
        $query->limit(10); // Giới hạn 10 món ăn bán chạy nhất
    
        $queryResult = $query->get();
    
        // Nếu không có kết quả, trả về JSON báo không có dữ liệu
        if ($queryResult->isEmpty()) {
            return response()->json([
                'message' => 'Không có dữ liệu thống kê'
            ]);
        }
    
        // Trích xuất dữ liệu
        $labels = $queryResult->pluck('ten');
        $datasets = $queryResult->pluck('tong_so_luong');
    
        // Kiểm tra nếu request là AJAX thì trả về JSON
        if ($request->ajax()) {
            return response()->json([
                'labels' => $labels->toArray(),
                'datasets' => $datasets->toArray()
            ]);
        }
    
        // Trả về view với dữ liệu đã lọc
        return view('admin.thongke.thongkemonan', compact('labels', 'datasets', 'filterType', 'fromDate', 'toDate'));
    }
        
}
