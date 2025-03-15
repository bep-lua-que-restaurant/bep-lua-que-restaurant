<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TinNhan;
use App\Models\BanAn;
use App\Models\HoaDon;
use Carbon\Carbon;

class ChatController extends Controller
{
    public function index()
    {
        return view('admin.chatbox');
    }

    public function guiTinNhan(Request $request)
    {
        $request->validate([
            'nguoi_dung_id' => 'required|exists:nhan_viens,id',
            'noi_dung' => 'required|string',
        ]);

        $noiDung = mb_strtolower(trim($request->noi_dung), 'UTF-8'); // Xử lý tiếng Việt có dấu
        $phanHoi = "Xin lỗi, tôi không hiểu yêu cầu của bạn.";

        // 🔹 Kiểm tra trạng thái bàn
        if (preg_match('/trạng thái  (.+)/u', $noiDung, $matches)) {
            $tenBan = trim($matches[1]);
            $banAn = BanAn::where('ten_ban', $tenBan)->first();

            if ($banAn) {
                $trangThai = [
                    'trong' => 'Bàn đang trống, sẵn sàng phục vụ.',
                    'co_khach' => 'Bàn đang có khách.',
                    'đa_đat_truoc' => 'Bàn đã được đặt trước.'
                ];

                $phanHoi = "Bàn '{$banAn->ten_ban}' hiện đang ở trạng thái: " . ($trangThai[$banAn->trang_thai] ?? 'Không xác định');
            } else {
                $phanHoi = "Không tìm thấy bàn có tên '{$tenBan}'.";
            }
        }

        //  Thống kê tổng doanh thu
        if (strpos($noiDung, 'doanh thu tổng') !== false) {
            $tongDoanhThu = HoaDon::sum('tong_tien');
            $phanHoi = "Tổng doanh thu hiện tại là: " . number_format($tongDoanhThu, 0, ',', '.') . " VNĐ";
        }

        // Thống kê doanh thu theo ngày
        // Thống kê doanh thu theo ngày (định dạng DD-MM-YYYY)
        if (preg_match('/doanh thu ngày (\d{2}-\d{2}-\d{4})/u', $noiDung, $matches)) {
            $ngay = $matches[1];

            // Chuyển đổi sang định dạng chuẩn để truy vấn database
            $ngayFormatted = Carbon::createFromFormat('d-m-Y', $ngay)->format('Y-m-d');
            $doanhThu = HoaDon::whereDate('created_at', $ngayFormatted)->sum('tong_tien');

            $phanHoi = "Doanh thu ngày $ngay là: " . number_format($doanhThu, 0, ',', '.') . " VNĐ";
        }


        //  Lưu tin nhắn vào database
        $tinNhan = TinNhan::create([
            'nguoi_dung_id' => $request->nguoi_dung_id,
            'ten' => $request->ten ?? 'Người dùng',
            'noi_dung' => $request->noi_dung,
            'nguon_tu_bot' => true,
            'nguon_tu_nhan_vien' => false,
        ]);

        return response()->json([
            'tin_nhan' => $tinNhan,
            'phan_hoi' => $phanHoi
        ]);
    }
}
