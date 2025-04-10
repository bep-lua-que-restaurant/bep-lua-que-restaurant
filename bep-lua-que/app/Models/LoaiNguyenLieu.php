<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LoaiNguyenLieu extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'loai_nguyen_lieus'; // Tên bảng trong cơ sở dữ liệu

    protected $fillable = [
        'ten_loai', // Tên loại nguyên liệu
        'ghi_chu',  // Ghi chú
    ];

    /**
     * Quan hệ với bảng `nguyen_lieus` (1 loại nguyên liệu có nhiều nguyên liệu).
     */
    public function nguyenLieus()
    {
        return $this->hasMany(NguyenLieu::class, 'loai_nguyen_lieu_id', 'id');
    }
}