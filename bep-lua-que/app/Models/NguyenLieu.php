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
        'don_vi_tinh',
        'so_luong_ton',
        'so_luong_ton_toi_thieu',
        'so_luong_ton_toi_da',
        'gia_nhap',
        'hinh_anh',
        'mo_ta',
    ];

    // Quan hệ với bảng nhập kho (Chi tiết nhập hàng)
    public function chiTietNhapKho()
    {
        return $this->hasMany(ChiTietNhapKho::class, 'nguyen_lieu_id');
    }
}
