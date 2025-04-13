<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TinNhan extends Model
{
    use HasFactory;
    protected $fillable = [
        'nguoi_dung_id',
        'ten',
        'noi_dung',
        'nguon_tu_bot',
        'nguon_tu_nhan_vien',
    ];

    /**
     * Liên kết với model NhanVien (người gửi tin nhắn)
     */
    public function nguoiDung()
    {
        return $this->belongsTo(NhanVien::class, 'nguoi_dung_id');
    }
}
