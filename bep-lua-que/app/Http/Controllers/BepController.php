<?php

namespace App\Http\Controllers;

use App\Models\HoaDonBan;
use Illuminate\Http\Request;
use App\Models\ChiTietHoaDon;

class BepController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index()
    {
        $monAnChoCheBien = ChiTietHoaDon::with(['monAn', 'hoaDon.banAns'])
            ->where('trang_thai', 'cho_che_bien')
            ->get();


        $monAnDangNau = ChiTietHoaDon::with(['monAn', 'hoaDon.banAns'])
            ->where('trang_thai', 'dang_nau')
            ->get();

        return view('gdnhanvien.bep.index', compact('monAnChoCheBien', 'monAnDangNau'));
    }

    public function updateTrangThai(Request $request, $id)
    {
        $mon = ChiTietHoaDon::find($id);

        if (!$mon) {
            return response()->json(['success' => false, 'message' => 'Không tìm thấy món ăn.']);
        }

        $mon->trang_thai = $request->trang_thai;
        $mon->save();

        return response()->json(['success' => true, 'message' => 'Cập nhật trạng thái thành công.']);
    }
}
