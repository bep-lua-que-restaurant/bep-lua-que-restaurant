<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Luong;
use Carbon\Carbon;
use App\Models\NhanVien;

use App\Models\BangTinhLuong; 
use App\Models\LichSuLuong;// <--- Thêm dòng này


class LuongController extends Controller
{
    public function index(Request $request)
    {
        // Lấy tháng-năm từ dropdown hoặc mặc định là tháng hiện tại
        $thangNam = $request->input('thang_nam', Carbon::now()->format('Y-m'));
    
        // Tạo ngày bắt đầu và kết thúc tháng
        $startDate = Carbon::createFromFormat('Y-m', $thangNam)->startOfMonth();
        $endDate = Carbon::createFromFormat('Y-m', $thangNam)->endOfMonth();
    
        // Chỉ lấy dữ liệu nằm trong khoảng này
        $luongs = Luong::whereBetween('ngay_tinh_luong', [$startDate, $endDate])->get();
    
        return view('admin.luong.index', compact('luongs', 'thangNam'));
    }
    public function chotLuong(Request $request)
    {
        $thangNam = $request->input('thang_nam'); // Ví dụ: '2025-04'

        $dsNhanVien = NhanVien::all();

        foreach ($dsNhanVien as $nv) {
            BangTinhLuong::create([
                'nhan_vien_id' => $nv->id,
                'so_cong' => 0,
                'muc_luong' => $nv->muc_luong_hien_tai,
                'tong_luong' => 0,
                'thang_nam' => $thangNam,
            ]);
        }

        return redirect()->back()->with('success', 'Đã chốt lương cho tháng ' . $thangNam);
    }




    public function create()
    {
        // $nhanViens = NhanVien::all();
        // return view('admin.bangluong.tinhluong', compact('nhanViens'));
    }

    public function store(Request $request)
    {
        // $validated = $request->validate([
        //     'nhan_vien_id' => 'required',
        //     'hinh_thuc' => 'required',
        //     'muc_luong' => 'required|numeric',
        //     'so_gio_lam' => 'nullable|numeric',
        //     'so_ngay_lam' => 'nullable|numeric',
        // ]);

        // // Tính lương dựa trên hình thức
        // $tong_luong = 0;
        // if ($request->hinh_thuc == 'gio') {
        //     $tong_luong = $request->muc_luong * $request->so_gio_lam;
        // } elseif ($request->hinh_thuc == 'ngay') {
        //     $tong_luong = $request->muc_luong * $request->so_ngay_lam;
        // } elseif ($request->hinh_thuc == 'thang') {
        //     $tong_luong = $request->muc_luong;
        // }

        // Luong::create([
        //     'nhan_vien_id' => $request->nhan_vien_id,
        //     'hinh_thuc' => $request->hinh_thuc,
        //     'muc_luong' => $request->muc_luong,
        //     'so_gio_lam' => $request->so_gio_lam,
        //     'so_ngay_lam' => $request->so_ngay_lam,
        //     'tong_luong' => $tong_luong
        // ]);

        // return redirect()->route('luong.luong')->with('success', 'Lương đã được tính!');
    }

    public function capNhatLuong(Request $request, $idNhanVien)
    {
        $request->validate([
            'luong' => 'required|numeric',
            'thang' => 'required|integer',
            'nam' => 'required|integer',
        ]);

        // Lấy nhân viên
        $nhanVien = NhanVien::findOrFail($idNhanVien);

        // Lưu lương cũ cho tháng trước
        if ($request->thang > 1) {
            LichSuLuong::create([
                'id_nhan_vien' => $idNhanVien,
                'luong' => 5000000, // Lương cũ
                'thang' => $request->thang - 1,
                'nam' => $request->nam,
            ]);
        }

        // Lưu lương mới cho tháng hiện tại
        LichSuLuong::create([
            'id_nhan_vien' => $idNhanVien,
            'luong' => $request->luong, // Lương mới
            'thang' => $request->thang,
            'nam' => $request->nam,
        ]);

        return response()->json(['message' => 'Cập nhật lương thành công!']);
    }

    public function layLuong($idNhanVien, $thang, $nam)
    {
        $luong = LichSuLuong::where('id_nhan_vien', $idNhanVien)
            ->where('thang', $thang)
            ->where('nam', $nam)
            ->first();

        return response()->json($luong);
    }

}
