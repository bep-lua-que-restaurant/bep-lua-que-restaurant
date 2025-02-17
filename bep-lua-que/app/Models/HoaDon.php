<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class HoaDon extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['ma_hoa_don', 'khach_hang_id', 'tong_tien', 'phuong_thuc_thanh_toan', 'mo_ta'];

    public function chiTietHoaDons()
    {
        return $this->hasMany(ChiTietHoaDon::class, 'hoa_don_id');
    }

    public function hoaDonBans()
    {
        return $this->hasMany(HoaDonBan::class, 'hoa_don_id');
    }

    public function banAns()
    {

        return $this->belongsToMany(BanAn::class, 'hoa_don_bans', 'hoa_don_id', 'ban_an_id');

    }
}
