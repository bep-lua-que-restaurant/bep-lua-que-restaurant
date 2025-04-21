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
        'thoi_gian_nau',
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
        return $this->hasMany(HinhAnhMonAn::class)->withTrashed();
    }


    public function chiTietHoaDons()
    {
        return $this->hasMany(ChiTietHoaDon::class, 'mon_an_id');
    }
    public static function getMonAnYeuThich($limit = 3)
    {
        return DB::table('chi_tiet_hoa_dons')
            ->join('mon_ans', 'chi_tiet_hoa_dons.mon_an_id', '=', 'mon_ans.id')
            ->select('mon_ans.ten as ten', DB::raw('SUM(chi_tiet_hoa_dons.so_luong) as tong_so_luong'))
            ->groupBy('mon_ans.id', 'mon_ans.ten')
            ->orderByDesc('tong_so_luong')
            ->limit($limit)
            ->get();
    }
}
