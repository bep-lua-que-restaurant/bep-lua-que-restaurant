<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CaLamNhanVien extends Model
{

    use HasFactory, SoftDeletes;

    protected $table = 'ca_lam_nhan_viens';

    protected $fillable = [
        'ca_lam_id',
        'nhan_vien_id',
        'ngay_lam',
        'gio_bat_dau',
        'gio_ket_thuc',
        'mo_ta',
        'trang_thai',
    ];

    /**
     * Quan hệ với model CaLam (định nghĩa bảng ca làm)
     */
    public function caLam()
    {
        return $this->belongsTo(CaLam::class, 'ca_lam_id');
    }

    /**
     * Quan hệ với model NhanVien (định nghĩa bảng nhân viên)
     */
    public function nhanVien()
    {
        return $this->belongsTo(NhanVien::class, 'nhan_vien_id');
    }
    //     public function ca()
    // {
    //     return $this->belongsTo(CaLam::class, 'ca_lam_id');
    // }

}
