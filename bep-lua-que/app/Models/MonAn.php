<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class MonAn extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'danh_muc_mon_an_id',
        'ten',
        'mo_ta',
        'gia',
        'trang_thai',
        'thoi_gian_nau'

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
    public static function getMonAnYeuThich($limit = 3)
    {
        return self::withCount(['chiTietHoaDons as tong_so_luong' => function ($query) {
            $query->select(DB::raw('SUM(so_luong)'));
        }])
            ->orderByDesc('tong_so_luong')
            ->limit($limit)
            ->get(['id', 'ten_mon_an']);
    }
}
