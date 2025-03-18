<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HoaDonBan extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['hoa_don_id', 'ban_an_id', 'trang_thai', 'ma_dat_ban'];

    public function banAn()
    {
        return $this->belongsTo(BanAn::class, 'ban_an_id');
    }
}
