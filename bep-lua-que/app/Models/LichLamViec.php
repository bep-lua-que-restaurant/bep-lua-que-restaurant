<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LichLamViec extends Model
{
    use HasFactory;

    protected $table = 'ca_lam_nhan_viens';

    protected $fillable = [
        'nhan_vien_id',
        'ca_lam_id',
        'ngay_lam',
        'xac_nhan'
    ];

    public function nhanVien()
    {
        return $this->belongsTo(NhanVien::class, 'nhan_vien_id');
    }

    public function caLam()
    {
        return $this->belongsTo(CaLam::class, 'ca_lam_id');
    }
}