<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NguyenLieu extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'nguyen_lieus'; // Tên bảng trong cơ sở dữ liệu

    protected $fillable = [
        'ten_nguyen_lieu',      // Tên nguyên liệu
        'loai_nguyen_lieu_id',  // ID loại nguyên liệu
        'don_vi_ton',           // Đơn vị tồn kho
        'so_luong_ton',         // Số lượng tồn kho
        'ghi_chu',              // Ghi chú
    ];

    /**
     * Quan hệ với bảng `loai_nguyen_lieus` (1 nguyên liệu thuộc 1 loại nguyên liệu).
     */
    public function loaiNguyenLieu()
    {
        return $this->belongsTo(LoaiNguyenLieu::class, 'loai_nguyen_lieu_id', 'id');
    }

    /**
     * Quan hệ với bảng `chi_tiet_phieu_nhap_khos` (1 nguyên liệu có thể xuất hiện trong nhiều phiếu nhập).
     */
    public function chiTietPhieuNhapKhos()
    {
        return $this->hasMany(ChiTietPhieuNhapKho::class, 'nguyen_lieu_id', 'id');
    }

    /**
     * Quan hệ với bảng `chi_tiet_phieu_xuat_khos` (1 nguyên liệu có thể xuất hiện trong nhiều phiếu xuất).
     */
    public function chiTietPhieuXuatKhos()
    {
        return $this->hasMany(ChiTietPhieuXuatKho::class, 'nguyen_lieu_id', 'id');
    }
}