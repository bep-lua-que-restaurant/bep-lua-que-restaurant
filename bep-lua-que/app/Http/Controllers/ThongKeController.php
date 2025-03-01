<?php

namespace App\Http\Controllers;

use App\Models\DatBan;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\HoaDon;

class ThongKeController extends Controller
{
    public function index(Request $request)
    {
        $filterType = $request->input('filterType', 'day');
        $filterType2 = $request->input('filterType2', 'day'); // Thêm lọc số lượng khách
        $data = [];
        $labels = [];
        $customerData = [];
        $customerLabels = [];
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();

        // Tổng tiền của đơn đã xong hôm nay
        $totalRevenueToday = HoaDon::whereDate('created_at', $today)->sum('tong_tien');

        // Tổng tiền của đơn đã xong hôm qua
        $totalRevenueYesterday = HoaDon::whereDate('created_at', $yesterday)->sum('tong_tien');

        // Thống kê theo năm, tháng, ngày
        if ($filterType == 'year') {
            $year = Carbon::now()->year;
            $labels = array_map(fn($m) => "Tháng $m", range(1, 12));
            $rawData = HoaDon::selectRaw('MONTH(created_at) as month, SUM(tong_tien) as revenue')
                ->whereYear('created_at', $year)
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
            $rawData = HoaDon::selectRaw('DAY(created_at) as day, SUM(tong_tien) as revenue')
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->groupBy('day')
                ->orderBy('day')
                ->pluck('revenue', 'day')
                ->toArray();

            foreach (range(1, $daysInMonth) as $day) {
                $data[] = $rawData[$day] ?? 0;
            }
        } elseif ($filterType == 'day') {
            $date = Carbon::now()->toDateString();
            $labels = array_map(fn($h) => "$h:00", range(0, 23));
            $rawData = HoaDon::selectRaw('HOUR(created_at) as hour, SUM(tong_tien) as revenue')
                ->whereDate('created_at', $date)
                ->groupBy('hour')
                ->orderBy('hour')
                ->pluck('revenue', 'hour')
                ->toArray();

            foreach (range(0, 23) as $hour) {
                $data[] = $rawData[$hour] ?? 0;
            }
        }

        // **Thống kê doanh số tháng này vs tháng trước để vẽ biểu đồ tròn**
        $currentMonthRevenue = HoaDon::whereYear('created_at', Carbon::now()->year)
            ->whereMonth('created_at', Carbon::now()->month)
            ->sum('tong_tien');

        $lastMonthRevenue = HoaDon::whereYear('created_at', Carbon::now()->year)
            ->whereMonth('created_at', Carbon::now()->subMonth()->month)
            ->sum('tong_tien');

        // **Thống kê doanh số tuần này vs tuần trước để vẽ biểu đồ tròn**
        $currentWeekRevenue = HoaDon::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->sum('tong_tien');

        $lastWeekRevenue = HoaDon::whereBetween('created_at', [Carbon::now()->subWeek()->startOfWeek(), Carbon::now()->subWeek()->endOfWeek()])
            ->sum('tong_tien');

        // Tính phần trăm chênh lệch và làm tròn 2 số thập phân
        $monthPercentage = $lastMonthRevenue > 0
            ? round((($currentMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100, 2)
            : 0;

        $weekPercentage = $lastWeekRevenue > 0
            ? round((($currentWeekRevenue - $lastWeekRevenue) / $lastWeekRevenue) * 100, 2)
            : 0;


        // Tổng số khách hôm nay
        $customersToday = DatBan::whereDate('created_at', $today)->sum('so_nguoi');

        // Tổng số khách hôm qua
        $customersYesterday = DatBan::whereDate('created_at', $yesterday)->sum('so_nguoi');

        // Thống kê số lượng khách theo bộ lọc
        if ($filterType2 == 'year') {
            $year = Carbon::now()->year;
            $customerLabels = array_map(fn($m) => "Tháng $m", range(1, 12));
            $rawCustomerData = DatBan::selectRaw('MONTH(created_at) as month, SUM(so_nguoi) as total_customers')
                ->whereYear('created_at', $year)
                ->groupBy('month')
                ->orderBy('month')
                ->pluck('total_customers', 'month')
                ->toArray();

            foreach (range(1, 12) as $month) {
                $customerData[] = $rawCustomerData[$month] ?? 0;
            }
        } elseif ($filterType2 == 'month') {
            $year = Carbon::now()->year;
            $month = Carbon::now()->month;
            $daysInMonth = Carbon::now()->daysInMonth;
            $customerLabels = array_map(fn($d) => "Ngày $d", range(1, $daysInMonth));
            $rawCustomerData = DatBan::selectRaw('DAY(created_at) as day, SUM(so_nguoi) as total_customers')
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->groupBy('day')
                ->orderBy('day')
                ->pluck('total_customers', 'day')
                ->toArray();

            foreach (range(1, $daysInMonth) as $day) {
                $customerData[] = $rawCustomerData[$day] ?? 0;
            }
        } elseif ($filterType2 == 'day') {
            $date = Carbon::now()->toDateString();
            $customerLabels = array_map(fn($h) => str_pad($h, 2, '0', STR_PAD_LEFT) . ':00', range(0, 23));

            // Lấy dữ liệu khách theo từng giờ
            $rawCustomerData = DatBan::selectRaw('HOUR(created_at) as hour, SUM(so_nguoi) as total_customers')
                ->whereDate('created_at', $date)
                ->groupBy('hour')
                ->orderBy('hour')
                ->pluck('total_customers', 'hour')
                ->toArray();

            // Khởi tạo mảng customerData với giá trị 0
            $customerData = array_fill(0, 24, 0);

            // Gán giá trị từ database vào đúng giờ
            foreach ($rawCustomerData as $hour => $total) {
                $customerData[(int)$hour] = $total;
            }
        }

        // **Thống kê số lượng khách tháng này vs tháng trước để vẽ biểu đồ tròn**
        $currentMonthCustomers = DatBan::whereYear('created_at', Carbon::now()->year)
            ->whereMonth('created_at', Carbon::now()->month)
            ->sum('so_nguoi');

        $lastMonthCustomers = DatBan::whereYear('created_at', Carbon::now()->year)
            ->whereMonth('created_at', Carbon::now()->subMonth()->month)
            ->sum('so_nguoi');

        // **Thống kê doanh số tuần này vs tuần trước để vẽ biểu đồ tròn**
        $currentWeekCustomers = DatBan::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->sum('so_nguoi');

        $lastWeekCustomers = DatBan::whereBetween('created_at', [Carbon::now()->subWeek()->startOfWeek(), Carbon::now()->subWeek()->endOfWeek()])
            ->sum('so_nguoi');

        // Tính phần trăm chênh lệch và làm tròn 2 số thập phân
//        $monthComparisonPercentage = $lastMonthCustomers > 0
//            ? round((($currentMonthCustomers - $lastMonthCustomers) / $lastMonthCustomers) * 100, 2)
//            : 0;
//
//        $weekComparisonPercentage = $lastWeekCustomers > 0
//            ? round((($currentWeekCustomers - $lastWeekCustomers) / $lastWeekCustomers) * 100, 2)
//            : 0;
        // Tính phần trăm thay đổi
        $monthComparisonPercentage = $lastMonthCustomers != 0 ? round((($currentMonthCustomers - $lastMonthCustomers) / $lastMonthCustomers) * 100, 2) : 0;
        $weekComparisonPercentage = $lastWeekCustomers != 0 ? round((($currentWeekCustomers - $lastWeekCustomers) / $lastWeekCustomers) * 100, 2) : 0;


        if ($request->ajax()) {
            return response()->json([
                'labels' => $labels,
                'data' => $data,
                'customerLabels' => $customerLabels,
                'customerData' => $customerData,
                'totalSales' => number_format(array_sum($data), 0, ',', '.') . ' VND',
                'soKhach' => array_sum($customerData),
                'filterType' => $filterType, // Trả về loại bộ lọc để cập nhật tiêu đề
                'filterType2' => $filterType2,
                'totalRevenueToday' => number_format($totalRevenueToday, 0, ',', '.') . ' VND',
                'totalRevenueYesterday' => number_format($totalRevenueYesterday, 0, ',', '.') . ' VND',
                'customersToday' => $customersToday,
                'customersYesterday' => $customersYesterday,
                'monthComparison' => [
                    'currentMonth' => $currentMonthRevenue,
                    'lastMonth' => $lastMonthRevenue,
                    'percentage' => round($monthPercentage, 2)
                ],
                'weekComparison' => [
                    'currentWeek' => $currentWeekRevenue,
                    'lastWeek' => $lastWeekRevenue,
                    'percentage' => round($weekPercentage, 2)
                ],
                'currentMonthCustomers' => $currentMonthCustomers,
                'lastMonthCustomers' => $lastMonthCustomers,
                'monthComparisonPercentage' => $monthComparisonPercentage,

                'currentWeekCustomers' => $currentWeekCustomers,
                'lastWeekCustomers' => $lastWeekCustomers,
                'weekComparisonPercentage' => $weekComparisonPercentage,
            ]);
        }

        return view('admin.dashboard', compact('labels', 'data',
                                            'customerLabels', 'customerData', 'filterType', 'filterType2',
                                            'totalRevenueToday', 'totalRevenueYesterday',
                                            'customersToday', 'customersYesterday',
                                            'currentMonthRevenue', 'lastMonthRevenue',
                                            'currentWeekRevenue', 'lastWeekRevenue',
                                            'monthPercentage', 'weekPercentage',
                                            'currentMonthCustomers', 'lastMonthCustomers',
                                            'currentWeekCustomers', 'lastWeekCustomers',
                                            'monthComparisonPercentage', 'weekComparisonPercentage'));
    }
}
