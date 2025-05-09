<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChiTietHoaDon extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['hoa_don_id', 'mon_an_id', 'so_luong', 'don_gia', 'thanh_tien', 'trang_thai'];
    protected $casts = [
        'thoi_gian_hoan_thanh_du_kien' => 'datetime',
    ];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
    public function monAn()
    {
        return $this->belongsTo(MonAn::class, 'mon_an_id', 'id');
    }

    public function hoaDon()
    {
        return $this->belongsTo(HoaDon::class, 'hoa_don_id');
    }
}
