<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TinNhan;
use App\Models\BanAn;
use App\Models\HoaDon;
use App\Models\HoaDonBan;
use App\Models\MonAn; // Thêm model MonAn
use Carbon\Carbon;

class ChatController extends Controller
{
    public function index()
    {
        return view  ('admin.chatbox');
    }

    public function guiTinNhan(Request $request)
    {
        $request->validate([
            'nguoi_dung_id' => 'required|exists:nhan_viens,id',
            'noi_dung' => 'required|string',
        ]);

        $noiDung = mb_strtolower(trim($request->noi_dung), 'UTF-8');
        $phanHoi = "Xin lỗi, tôi không hiểu yêu cầu của bạn.";

        // ==== CHÀO HỎI ====
        if (preg_match('/(xin chào|chào|hello|hi)/u', $noiDung)) {
            $phanHoi = "Xin chào bạn! Tôi có thể giúp gì cho bạn hôm nay?";
        }

        // ==== TRẠNG THÁI BÀN ====
        if (preg_match('/trạng thái\s+(.+)/u', $noiDung, $matches)) {
            $tenBan = trim($matches[1]);
            $banAn = BanAn::where('ten_ban', $tenBan)->first();

            if ($banAn) {
                $trangThai = [
                    'trong' => 'Bàn đang trống, sẵn sàng phục vụ.',
                    'co_khach' => 'Bàn đang có khách.',
                    'da_dat_truoc' => 'Bàn đã được đặt trước.'
                ];
                $phanHoi = "Bàn '{$banAn->ten_ban}' hiện đang ở trạng thái: " . ($trangThai[$banAn->trang_thai] ?? 'Không xác định');
            } else {
                $phanHoi = "Không tìm thấy bàn có tên '{$tenBan}'.";
            }
        }

        // ==== DOANH THU TỔNG ====
        if (strpos($noiDung, 'doanh thu tổng') !== false && !preg_match('/từ.+đến/u', $noiDung)) {
            $tongDoanhThu = HoaDon::whereHas('hoaDonBan', function ($query) {
                $query->where('trang_thai', 'da_thanh_toan');
            })->sum('tong_tien');

            $phanHoi = "Tổng doanh thu hiện tại là: " . number_format($tongDoanhThu, 0, ',', '.') . " VNĐ";
        }

        // ==== DOANH THU THEO NGÀY ====
        if (preg_match('/doanh thu ngày (\d{2}-\d{2}-\d{4})/u', $noiDung, $matches)) {
            $ngay = $matches[1];
            $ngayFormatted = Carbon::createFromFormat('d-m-Y', $ngay)->format('Y-m-d');

            $doanhThu = HoaDon::whereDate('created_at', $ngayFormatted)
                ->whereHas('hoaDonBan', function ($query) {
                    $query->where('trang_thai', 'da_thanh_toan');
                })->sum('tong_tien');

            $phanHoi = "Doanh thu ngày $ngay là: " . number_format($doanhThu, 0, ',', '.') . " VNĐ";
        }

        // ==== DOANH THU THEO KHOẢNG THỜI GIAN ====
        if (preg_match('/doanh thu từ (\d{2}-\d{2}-\d{4}) đến (\d{2}-\d{2}-\d{4})/u', $noiDung, $matches)) {
            $tuNgay = Carbon::createFromFormat('d-m-Y', $matches[1])->startOfDay();
            $denNgay = Carbon::createFromFormat('d-m-Y', $matches[2])->endOfDay();

            $doanhThu = HoaDon::whereBetween('created_at', [$tuNgay, $denNgay])
                ->whereHas('hoaDonBan', function ($query) {
                    $query->where('trang_thai', 'da_thanh_toan');
                })->sum('tong_tien');

            $phanHoi = "Doanh thu từ ngày {$matches[1]} đến {$matches[2]} là: " . number_format($doanhThu, 0, ',', '.') . " VNĐ";
        }

        // ==== MÓN ĂN YÊU THÍCH ====
        if (preg_match('/(món ăn yêu thích|món bán chạy|món nhiều)/u', $noiDung)) {
            $monAnYeuThich = MonAn::getMonAnYeuThich(); // Gọi model đã viết

            if ($monAnYeuThich->isEmpty()) {
                $phanHoi = "Hiện tại chưa có dữ liệu món ăn yêu thích!";
            } else {
                $phanHoi = "Top món ăn được đặt nhiều nhất:\n";
                foreach ($monAnYeuThich as $mon) {
                    $phanHoi .= "- " . $mon->ten . " (Đã đặt: " . $mon->tong_so_luong . " lần)\n";
                }
            }
        }

        // ==== LƯU TIN NHẮN ====
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
