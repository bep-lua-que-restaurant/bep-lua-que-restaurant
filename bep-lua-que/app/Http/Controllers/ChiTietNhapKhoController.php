<?php

namespace App\Http\Controllers;

use App\Models\ChiTietNhapKho;
use App\Http\Requests\StoreChiTietNhapKhoRequest;
use App\Models\NguyenLieu;
use Illuminate\Http\Request;

class ChiTietNhapKhoController extends Controller
{
    public function store(StoreChiTietNhapKhoRequest $request)
    {
        $data = $request->validated();
    
        // Thêm chi tiết nhập kho
        $chiTiet = ChiTietNhapKho::create([
            'phieu_nhap_kho_id' => $data['phieu_nhap_kho_id'],
            'nguyen_lieu_id' => $data['nguyen_lieu_id'],
            'so_luong' => $data['so_luong'],
            'gia_nhap' => $data['gia_nhap'],
            'thanh_tien' => $data['so_luong'] * $data['gia_nhap'],
        ]);
    
        // Cập nhật số lượng nguyên liệu trong bảng nguyen_lieus
        $nguyenLieu = NguyenLieu::find($data['nguyen_lieu_id']);
        if ($nguyenLieu) {
            $nguyenLieu->increment('so_luong_ton', $data['so_luong']);
        }
    
        return back()->with('success', 'Thêm nguyên liệu vào phiếu nhập thành công!');
    }
    
    public function destroy(ChiTietNhapKho $chiTietNhapKho)
{
    // Giảm số lượng nguyên liệu trong kho
    $nguyenLieu = NguyenLieu::find($chiTietNhapKho->nguyen_lieu_id);
    if ($nguyenLieu) {
        $nguyenLieu->decrement('so_luong_ton', $chiTietNhapKho->so_luong);
    }

    // Xóa chi tiết nhập kho
    $chiTietNhapKho->delete();

    return back()->with('success', 'Xóa nguyên liệu khỏi phiếu nhập thành công!');
}

}
