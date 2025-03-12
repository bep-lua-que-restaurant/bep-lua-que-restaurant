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
    $filterType = $request->input('filterType', 'day');
    $fromDate = $request->input('fromDate');
    $toDate = $request->input('toDate');

    // Gán giá trị mặc định nếu không có fromDate hoặc toDate
    if (!$fromDate || !$toDate) {
        $fromDate = Carbon::now()->subDays(7)->format('Y-m-d');
        $toDate = Carbon::now()->format('Y-m-d');
    }

    $fromDate = Carbon::parse($fromDate);
    $toDate = Carbon::parse($toDate);

    switch ($filterType) {
        case 'month':
            $fromDate = $fromDate->startOfMonth();
            $toDate = $toDate->endOfMonth();
            break;
        case 'year':
            $fromDate = $fromDate->startOfYear();
            $toDate = $toDate->endOfYear();
            break;
        default:
            $fromDate = $fromDate->startOfDay();
            $toDate = $toDate->endOfDay();
            break;
    }

    // Truy vấn dữ liệu thống kê
    $query = DB::table('chi_tiet_hoa_dons')
        ->join('mon_ans', 'chi_tiet_hoa_dons.mon_an_id', '=', 'mon_ans.id')
        ->where('chi_tiet_hoa_dons.trang_thai', 'hoan_thanh')
        ->whereBetween('chi_tiet_hoa_dons.created_at', [$fromDate, $toDate])
        ->select(
            'mon_ans.id',
            'mon_ans.ten',
            DB::raw('SUM(chi_tiet_hoa_dons.so_luong) as tong_so_luong')
        )
        ->groupBy('mon_ans.id', 'mon_ans.ten')
        ->orderByDesc('tong_so_luong')
        ->limit(10);

    $queryResult = $query->get();

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
