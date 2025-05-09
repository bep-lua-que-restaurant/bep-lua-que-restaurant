<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class HoaDon extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['ma_hoa_don', 'ma_dat_ban', 'tong_tien', 'phuong_thuc_thanh_toan', 'mo_ta', 'id_ma_giam','tong_tien_truoc_khi_giam'];

    public function chiTietHoaDons()
    {
        return $this->hasMany(ChiTietHoaDon::class, 'hoa_don_id', 'id');
    }

    public function hoaDonBans()
    {
        return $this->hasMany(HoaDonBan::class, 'hoa_don_id');
    }

    public function banAns()
    {
        return $this->belongsToMany(BanAn::class, 'hoa_don_bans', 'hoa_don_id', 'ban_an_id');
    }
    public function scopeTongDoanhThu($query)
    {
        return $query->where('deleted_at', null)->sum('tong_tien');
    }

    public function scopeDoanhThuTheoNgay($query, $ngay)
    {
        return $query->whereDate('created_at', $ngay)->sum('tong_tien');
    }
    public function hoaDonBan()
    {
        return $this->hasOne(HoaDonBan::class, 'hoa_don_id', 'id');
    }
    // Trong model HoaDon
    public function maGiamGia()
    {
        return $this->belongsTo(MaGiamGia::class, 'id_ma_giam', 'id');
    }

    // Quan hệ 1-N với BillImage (HoaDon có thể có nhiều ảnh)
    public function billImages()
    {
        return $this->hasMany(BillImage::class, 'hoa_don_id');
    }
}
