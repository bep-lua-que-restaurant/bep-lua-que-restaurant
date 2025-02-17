<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NhapKho extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'ma_nhap_kho',
        'nhan_vien_id',
        'kho_id',
        'nha_cung_cap_id',
        'ngay_nhap',
        'trang_thai'
    ];

    public function chiTiets()
    {
        return $this->hasMany(NhapKhoChiTiet::class);
    }

    public function nhaCungCap()
    {
        return $this->belongsTo(NhaCungCap::class);
    }
}
