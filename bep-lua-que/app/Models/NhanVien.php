<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NhanVien extends Authenticatable
{
    use HasFactory, SoftDeletes;

    protected $table = 'nhan_viens';
    protected $fillable = [
        'chuc_vu_id',
        'ma_nhan_vien',
        'ho_ten',
        'email',
        'so_dien_thoai',
        'password',
        'dia_chi',
        'hinh_anh',
        'gioi_tinh',
        'ngay_sinh',
        'ngay_vao_lam',
    ];

    public function chucVu()
    {
        return $this->belongsTo(ChucVu::class, 'chuc_vu_id');
    }
}
