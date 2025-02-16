<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChiTietPhieuNhapKho extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'chi_tiet_phieu_nhap_khos';

    protected $fillable = [
        'phieu_nhap_kho_id',
        'nguyen_lieu_id',
        'so_luong',
        'don_gia',
        'tong_tien',
        'han_su_dung',
        'trang_thai',
    ];

    public function phieuNhapKho()
    {
        return $this->belongsTo(PhieuNhapKho::class, 'phieu_nhap_kho_id');
    }

    public function nguyenLieu()
    {
        return $this->belongsTo(NguyenLieu::class, 'nguyen_lieu_id');
    }
}
