<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PhieuNhapKho extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'phieu_nhap_khos';

    protected $fillable = [
        'ma_phieu_nhap',
        'nhan_vien_id',
        'nha_cung_cap_id',
        'ngay_nhap',
        'trang_thai',
        'ghi_chu',
    ];


    public function nhaCungCap()
    {
        return $this->belongsTo(NhaCungCap::class, 'nha_cung_cap_id');
    }


    public function chiTietPhieuNhapKho()
    {
        return $this->hasMany(ChiTietPhieuNhapKho::class, 'phieu_nhap_kho_id');
    }
    // Quan hệ với nhân viên
    public function nhanVien()
    {
        return $this->belongsTo(NhanVien::class, 'nhan_vien_id');
    }

    public function nguyenLieu()
    {
        return $this->belongsToMany(NguyenLieu::class, 'chi_tiet_phieu_nhap', 'phieu_nhap_id', 'nguyen_lieu_id')
            ->withPivot('so_luong', 'gia'); // Nếu có thông tin phụ trong bảng trung gian
    }
}
