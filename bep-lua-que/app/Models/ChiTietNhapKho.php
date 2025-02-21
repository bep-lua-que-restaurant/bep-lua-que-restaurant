<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChiTietNhapKho extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'chi_tiet_nhap_khos';

    protected $fillable = [
        'phieu_nhap_kho_id',
        'nguyen_lieu_id',
        'loai_nguyen_lieu_id',
        'kho_id',
        'so_luong',
        'gia_nhap',
        'thanh_tien',
        'da_nhap_kho'
    ];

    // Quan hệ với phiếu nhập kho
    public function phieuNhapKho()
    {
        return $this->belongsTo(PhieuNhapKho::class, 'phieu_nhap_kho_id');
    }

    // Quan hệ với nguyên liệu
    public function nguyenLieu()
    {
        return $this->belongsTo(NguyenLieu::class, 'nguyen_lieu_id');
    }

    // Quan hệ với kho
    public function kho()
    {
        return $this->belongsTo(Kho::class, 'kho_id');
    }
}
