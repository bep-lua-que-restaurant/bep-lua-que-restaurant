<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DatBan extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['khach_hang_id', 'so_dien_thoai', 'thoi_gian_den', 'so_nguoi', 'trang_thai', 'ban_an_id', 'mo_ta'];

    public function banAn()
    {
        return $this->belongsTo(BanAn::class, 'ban_an_id');
    }
}
