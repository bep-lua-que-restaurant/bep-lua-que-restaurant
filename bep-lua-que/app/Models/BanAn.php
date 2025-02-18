<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BanAn extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['ten_ban', 'so_ghe', 'mo_ta', 'vi_tri'];



    // BanAn model
    public function phongAn()
    {
        return $this->belongsTo(PhongAn::class, 'vi_tri');  // 'vi_tri' là khóa ngoại trỏ đến id trong PhongAn
    }
    public function datBans()
    {
        return $this->hasMany(DatBan::class, 'ban_an_id');
    }
    public function hoaDons()
    {
        return $this->belongsToMany(HoaDon::class, 'hoa_don_bans', 'ban_an_id', 'hoa_don_id')
            ->withPivot('trang_thai')
            ->withTimestamps();
    }
}
