<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;


class DatBan extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'khach_hang_id',
        'so_dien_thoai',
        'thoi_gian_den',
        'so_nguoi',
        'trang_thai',
        'ban_an_id',
        'mo_ta',
        'ma_dat_ban',
        'gio_du_kien'
    ];
    protected $table = 'dat_bans';

    public static function generateMaDatBan()
    {
        do {
            $maDatBan = Str::upper(Str::random(11)); // Tạo mã ngẫu nhiên 11 ký tự chữ và số
        } while (self::where('ma_dat_ban', $maDatBan)->exists()); // Kiểm tra trùng lặp

        return $maDatBan;
    }


    public function banAn()
    {
        return $this->belongsTo(BanAn::class, 'ban_an_id');
    }

    public function khachHang()
    {
        return $this->belongsTo(KhachHang::class, 'khach_hang_id');
    }
}
