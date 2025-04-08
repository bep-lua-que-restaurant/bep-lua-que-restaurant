<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PhieuNhapKho extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'phieu_nhap_khos'; // Tên bảng trong cơ sở dữ liệu

    protected $fillable = [
        'ma_phieu',         // Mã phiếu nhập kho
        'ngay_nhap',        // Ngày nhập kho
        'nha_cung_cap_id',  // ID nhà cung cấp
        'nhan_vien_id',     // ID nhân viên thực hiện
        'tong_tien',        // Tổng tiền phiếu nhập
        'ghi_chu',          // Ghi chú
        'trang_thai',       // Trạng thái phiếu nhập
    ];

    /**
     * Quan hệ với bảng `nha_cung_caps` (1 phiếu nhập thuộc về 1 nhà cung cấp).
     */
    public function nhaCungCap()
    {
        return $this->belongsTo(NhaCungCap::class, 'nha_cung_cap_id', 'id');
    }

    /**
     * Quan hệ với bảng `nhan_viens` (1 phiếu nhập được thực hiện bởi 1 nhân viên).
     */
    public function nhanVien()
    {
        return $this->belongsTo(NhanVien::class, 'nhan_vien_id', 'id');
    }

    /**
     * Quan hệ với bảng `chi_tiet_phieu_nhap_khos` (1 phiếu nhập có nhiều chi tiết).
     */
    public function chiTietPhieuNhaps()
    {
        return $this->hasMany(ChiTietPhieuNhapKho::class, 'phieu_nhap_kho_id');
    }
}
