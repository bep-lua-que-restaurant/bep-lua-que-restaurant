<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BangTinhLuong extends Model
{
    use HasFactory;

    protected $table = 'bang_tinh_luongs';
    protected $fillable = ['nhan_vien_id', 'thang_nam', 'so_ca_lam', 'so_ngay_cong', 'tong_luong', 'ghi_chu'];

    public function nhanVien()
    {
        return $this->belongsTo(NhanVien::class, 'nhan_vien_id');
    }
    public function chamCongs()
{
    return $this->hasManyThrough(ChamCong::class, NhanVien::class, 'id', 'nhan_vien_id', 'nhan_vien_id', 'id');
}

}
