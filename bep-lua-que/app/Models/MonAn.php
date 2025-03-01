<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MonAn extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'danh_muc_mon_an_id',
        'ten',
        'mo_ta',
        'gia',
        'trang_thai',

    ];
    /**
     * Quan hệ với bảng DanhMucMonAn
     */
    public function danhMuc()
    {
        return $this->belongsTo(DanhMucMonAn::class, 'danh_muc_mon_an_id', 'id');
    }

    /**
     * Quan hệ với bảng HinhAnhMonAn
     */
    public function hinhAnhs()
    {
        return $this->hasMany(HinhAnhMonAn::class, 'mon_an_id', 'id');
    }


    public function chiTietHoaDons()
    {
        return $this->hasMany(ChiTietHoaDon::class, 'mon_an_id');
    }
    public function nguyenLieuMonAn()
    {
        return $this->hasMany(NguyenLieuMonAn::class, 'mon_an_id');
    }

    public function nguyenLieus()
    {
        return $this->belongsToMany(NguyenLieu::class, 'nguyen_lieu_mon_ans', 'mon_an_id', 'nguyen_lieu_id')
            ->withPivot('so_luong', 'don_vi_tinh') // Nếu bảng trung gian có thêm cột
            ->withTimestamps();
    }
}
