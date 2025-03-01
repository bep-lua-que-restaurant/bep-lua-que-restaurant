<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Luong;
use App\Models\NhanVien;


class LuongController extends Controller
{
    public function index(Request $request)
    {
    //     $query = Luong::query()
    //     ->leftJoin('nhan_viens', 'luongs.nhan_vien_id', '=', 'nhan_viens.id') // Join bảng nhân viên
    //     ->leftJoin('cham_congs', 'luongs.nhan_vien_id', '=', 'cham_congs.nhan_vien_id')
    //     ->leftJoin('ca_lams', 'cham_congs.ca_lam_id', '=', 'ca_lams.id')
    //     ->select(
    //         'luongs.*',
    //         'nhan_viens.ho_ten as ten_nhan_vien', // Lấy tên nhân viên
    //         'cham_congs.ngay_cham_cong',
    //         'cham_congs.gio_vao_lam',
    //         'cham_congs.gio_ket_thuc',
    //         'ca_lams.gio_bat_dau',
    //         'ca_lams.gio_ket_thuc'
    //     );
    
    // if ($request->has('ten') && $request->ten != '') {
    //     $query->where('luongs.ten_dich_vu', 'like', '%' . $request->ten . '%');
    // }
    
    // $data = $query->withTrashed()->latest('luongs.id')->paginate(15);
    
    // // Xử lý trả về khi yêu cầu là Ajax
    // if ($request->ajax()) {
    //     return response()->json([
    //         'html' => view('admin.dichvu.body-list', compact('data'))->render(),
    //     ]);
    // }
    
    // return view('admin.bangluong.list', [
    //     'data' => $data,
    //     'route' => route('dich-vu.index'),
    //     'tableId' => 'list-container',
    //     'searchInputId' => 'search-name',
    // ]);
    
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
}
