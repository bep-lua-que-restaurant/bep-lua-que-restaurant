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

}
