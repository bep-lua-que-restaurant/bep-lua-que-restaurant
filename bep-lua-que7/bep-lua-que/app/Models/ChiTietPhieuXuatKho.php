<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChiTietPhieuXuatKho extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'chi_tiet_phieu_xuat_khos'; // Tên bảng trong cơ sở dữ liệu

    protected $fillable = [
        'phieu_xuat_kho_id',  // ID phiếu xuất kho
        'nguyen_lieu_id',     // ID nguyên liệu
        'don_vi_xuat',        // Đơn vị xuất (ví dụ: kg, thùng, chai,...)
        'so_luong',           // Số lượng xuất theo đơn vị xuất
        'he_so_quy_doi',      // Hệ số quy đổi từ đơn vị xuất sang đơn vị tồn
        'don_gia',            // Giá của 1 đơn vị xuất
        'thanh_tien',         // Tổng tiền = so_luong * don_gia
        'ghi_chu',            // Ghi chú bổ sung
    ];

    /**
     * Quan hệ với bảng `phieu_xuat_khos` (1 chi tiết thuộc về 1 phiếu xuất kho).
     */
    public function phieuXuatKho()
    {
        return $this->belongsTo(PhieuXuatKho::class, 'phieu_xuat_kho_id', 'id');
    }

    /**
     * Quan hệ với bảng `nguyen_lieus` (1 chi tiết thuộc về 1 nguyên liệu).
     */
    public function nguyenLieu()
    {
        return $this->belongsTo(NguyenLieu::class, 'nguyen_lieu_id', 'id');
    }
}