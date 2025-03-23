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
        // Lấy ngày hôm nay (không bao gồm giờ, phút, giây)
        $today = Carbon::today();

        // Lấy ngày hôm qua (không bao gồm giờ, phút, giây)
        $yesterday = Carbon::yesterday();

        // Truy vấn tất cả hóa đơn có ngày tạo từ hôm qua trở đi
        $revenueData = HoaDon::whereDate('created_at', '>=', $yesterday)
            // Chọn ngày tạo (chỉ lấy phần ngày, không lấy giờ) và tổng doanh thu theo ngày
            ->selectRaw('DATE(created_at) as date, SUM(tong_tien) as revenue')
            // Gom nhóm theo ngày để tính tổng doanh thu mỗi ngày
            ->groupBy('date')
            // Trả về danh sách doanh thu dưới dạng mảng với key là ngày, value là tổng doanh thu
            ->pluck('revenue', 'date');

        // Lấy tổng doanh thu của ngày hôm nay từ mảng $revenueData
        // Chuyển đổi đối tượng $today thành chuỗi ngày (YYYY-MM-DD) để làm key
        // Nếu không tìm thấy dữ liệu trong mảng, mặc định là 0
        $totalRevenueToday = $revenueData[$today->toDateString()] ?? 0;

        // Lấy tổng doanh thu của ngày hôm qua từ mảng $revenueData
        // Chuyển đổi đối tượng $yesterday thành chuỗi ngày (YYYY-MM-DD) để làm key
        // Nếu không tìm thấy dữ liệu trong mảng, mặc định là 0
        $totalRevenueYesterday = $revenueData[$yesterday->toDateString()] ?? 0;

        // Số đơn hàng đang phục vụ hôm nay (trạng thái 'dang_xu_ly')
        $ordersServingToday = HoaDonBan::whereDate('created_at', $today) // Lọc các đơn hàng có ngày tạo là hôm nay
        // Chỉ lấy các đơn có trạng thái 'đang xử lý'
        ->where('trang_thai', 'dang_xu_ly')
        // Đếm tổng số đơn thỏa điều kiện
        ->count();

        // Số đơn hàng đã phục vụ hôm qua (trạng thái 'da_thanh_toan')
        $ordersCompletedYesterday = HoaDonBan::whereDate('created_at', $yesterday) // Lọc các đơn hàng có ngày tạo là hôm qua
        // Chỉ lấy các đơn có trạng thái 'đã thanh toán'
        ->where('trang_thai', 'da_thanh_toan')
        // Đếm tổng số đơn thỏa điều kiện
        ->count();

        // Truy vấn số khách đặt bàn hôm nay & hôm qua
        $customerData = DatBan::whereDate('created_at', '>=', $yesterday) // Lọc các lượt đặt bàn từ hôm qua trở đi
        // Chỉ lấy các đơn đã xác nhận
        ->where('trang_thai', 'xac_nhan')
        // Lấy ngày đặt bàn và tổng số khách trong ngày
        ->selectRaw('DATE(created_at) as date, SUM(so_nguoi) as total_customers')
        // Gom nhóm theo ngày
        ->groupBy('date')
        // Trả về danh sách số khách dưới dạng mảng với key là ngày, value là tổng khách
        ->pluck('total_customers', 'date');

        // Lấy tổng số khách hôm nay từ mảng $customerData
        // Chuyển đổi đối tượng $today thành chuỗi ngày (YYYY-MM-DD) để làm key
        // Nếu không tìm thấy dữ liệu trong mảng, mặc định là 0
        $customersToday = $customerData[$today->toDateString()] ?? 0;

        // Lấy tổng số khách hôm qua từ mảng $customerData
        // Chuyển đổi đối tượng $yesterday thành chuỗi ngày (YYYY-MM-DD) để làm key
        // Nếu không tìm thấy dữ liệu trong mảng, mặc định là 0
        $customersYesterday = $customerData[$yesterday->toDateString()] ?? 0;

        // Truy vấn doanh số theo giờ trong ngày hôm nay
        $salesData = HoaDon::whereDate('created_at', $today) // Lọc các hóa đơn có ngày tạo là hôm nay
        // Lấy giờ tạo hóa đơn và tổng doanh thu theo giờ
        ->selectRaw('HOUR(created_at) as hour, SUM(tong_tien) as revenue')
        // Gom nhóm theo giờ để tính tổng doanh thu của từng giờ
        ->groupBy('hour')
        // Trả về danh sách doanh thu dưới dạng mảng với key là giờ, value là tổng doanh thu
        ->pluck('revenue', 'hour');

        // Tạo nhãn thời gian từ 0:00 đến 23:00 cho biểu đồ hoặc báo cáo
        $labels = array_map(fn($h) => "$h:00", range(0, 23));

        // Tạo mảng dữ liệu doanh thu theo giờ, nếu giờ nào không có dữ liệu thì mặc định là 0
        $data = array_map(fn($h) => $salesData[$h] ?? 0, range(0, 23));

        // Kiểm tra nếu request là AJAX (tức là yêu cầu gửi từ JavaScript, không phải tải lại trang)
        if ($request->ajax()) {
            return response()->json([ // Trả về phản hồi dưới dạng JSON để frontend xử lý
                // Mảng nhãn thời gian từ "0:00" đến "23:00" để hiển thị trên biểu đồ
                'labels' => $labels,
                // Mảng doanh thu theo giờ, giá trị mặc định là 0 nếu không có dữ liệu
                'data' => $data,
                // Tổng doanh thu hôm nay (định dạng tiền tệ)
                'totalSales' => number_format($totalRevenueToday, 0, ',', '.') . ' VND',
                // Doanh thu hôm nay (định dạng số đẹp)
                'totalRevenueToday' => number_format($totalRevenueToday, 0, ',', '.') . ' VND',
                // Doanh thu hôm qua (định dạng số đẹp)
                'totalRevenueYesterday' => number_format($totalRevenueYesterday, 0, ',', '.') . ' VND',
                // Tổng số khách hôm nay
                'customersToday' => $customersToday,
                // Tổng số khách hôm qua
                'customersYesterday' => $customersYesterday,
                // Số đơn hàng đang phục vụ hôm nay (trạng thái "đang xử lý")
                'ordersServingToday' => $ordersServingToday,
                // Số đơn hàng đã hoàn thành hôm qua (trạng thái "đã thanh toán")
                'ordersCompletedYesterday' => $ordersCompletedYesterday
            ]);
        }

        // Trả về view 'admin.dashboard' và truyền dữ liệu xuống giao diện
        return view('admin.dashboard', compact(
            // Mảng nhãn giờ từ "0:00" đến "23:00" để hiển thị trên biểu đồ
            'labels',
            // Mảng doanh thu theo từng giờ trong ngày hôm nay
            'data',
            // Tổng doanh thu hôm nay
            'totalRevenueToday',
            // Tổng doanh thu hôm qua
            'totalRevenueYesterday',
            // Số đơn hàng đang phục vụ hôm nay (trạng thái "đang xử lý")
            'ordersServingToday',
            // Số đơn hàng đã hoàn thành hôm qua (trạng thái "đã thanh toán")
            'ordersCompletedYesterday',
            // Tổng số khách hôm nay
            'customersToday',
            // Tổng số khách hôm qua
            'customersYesterday'
        ));
    }
}
