<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PhieuXuatKho extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'phieu_xuat_khos'; // Tên bảng trong cơ sở dữ liệu

    protected $fillable = [
        'ma_phieu',         // Mã phiếu xuất kho
        'ngay_xuat',        // Ngày xuất kho
        'nhan_vien_id',     // ID nhân viên thực hiện
        'nguoi_nhan',       // Người nhận hàng
        'loai_phieu',       // Loại phiếu xuất (xuat_bep, xuat_tra_hang, xuat_huy)
        'nha_cung_cap_id',  // ID nhà cung cấp (nếu trả hàng)
        'tong_tien',        // Tổng tiền của phiếu xuất kho
        'ghi_chu',          // Ghi chú bổ sung
        'trang_thai',       // Trạng thái phiếu xuất (cho_duyet, da_duyet, da_huy)
    ];

    /**
     * Quan hệ với bảng `nhan_viens` (1 phiếu xuất được thực hiện bởi 1 nhân viên).
     */
    public function nhanVien()
    {
        return $this->belongsTo(NhanVien::class, 'nhan_vien_id', 'id');
    }

    /**
     * Quan hệ với bảng `nha_cung_caps` (1 phiếu xuất có thể liên quan đến 1 nhà cung cấp).
     */
    public function nhaCungCap()
    {
        return $this->belongsTo(NhaCungCap::class, 'nha_cung_cap_id', 'id');
    }

    /**
     * Quan hệ với bảng `chi_tiet_phieu_xuat_khos` (1 phiếu xuất có nhiều chi tiết).
     */
    public function chiTietPhieuXuatKhos()
    {
        return $this->hasMany(ChiTietPhieuXuatKho::class, 'phieu_xuat_kho_id', 'id');
    }
}