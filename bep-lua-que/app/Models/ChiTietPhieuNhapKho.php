<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChiTietPhieuNhapKho extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'chi_tiet_phieu_nhap_khos'; // Tên bảng trong cơ sở dữ liệu

    protected $fillable = [
        'phieu_nhap_kho_id',
        'ten_nguyen_lieu',
        'loai_nguyen_lieu_id',
        'nguyen_lieu_id',    // ID của nguyên liệu (có thể null nếu không có)
        'don_vi_nhap',

        'so_luong_nhap',

        'don_gia',
        'thanh_tien',
        'ngay_san_xuat',
        'han_su_dung',
        'ghi_chu'          // Ghi chú bổ sung
    ];

    /**
     * Quan hệ với bảng `phieu_nhap_khos` (1 chi tiết thuộc về 1 phiếu nhập kho).
     */
    public function phieuNhapKho()
    {
        return $this->belongsTo(PhieuNhapKho::class, 'phieu_nhap_kho_id', 'id');
    }

    /**
     * Quan hệ với bảng `nguyen_lieus` (1 chi tiết thuộc về 1 nguyên liệu).
     */
    public function nguyenLieu()
    {
        return $this->belongsTo(NguyenLieu::class, 'nguyen_lieu_id')->withTrashed();
    }

    /**
     * Quan hệ với bảng `loai_nguyen_lieus` (1 chi tiết thuộc về 1 loại nguyên liệu).
     */
    public function loaiNguyenLieu()
    {
        return $this->belongsTo(LoaiNguyenLieu::class, 'loai_nguyen_lieu_id', 'id');
    }
    
   

}
