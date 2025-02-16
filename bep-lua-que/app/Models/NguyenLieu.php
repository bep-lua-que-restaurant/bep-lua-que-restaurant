<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NguyenLieu extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'ma_nguyen_lieu',
        'ten_nguyen_lieu',
        'loai_nguyen_lieu_id',
        'don_vi_tinh',
        'so_luong_ton',
        'gia_nhap',
        'hinh_anh',
        'mo_ta',
    ];

    public function loaiNguyenLieu()
    {
        return $this->belongsTo(LoaiNguyenLieu::class, 'loai_nguyen_lieu_id');
    }

    public function chiTietPhieuNhapKhos()
    {
        return $this->hasMany(ChiTietPhieuNhapKho::class, 'nguyen_lieu_id');
    }
}
