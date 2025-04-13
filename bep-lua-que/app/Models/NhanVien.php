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


    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Mỗi nhân viên có thể nhập nhiều phiếu nhập kho.
     */
    // public function phieuNhapKho()
    // {
    //     return $this->hasMany(PhieuNhapKho::class, 'nhan_vien_id');
    // }

    public function luong()
    {
        return $this->hasMany(Luong::class,'nhan_vien_id','id');
    }
    public function chamCongs()
    {
        return $this->hasMany(ChamCong::class, 'nhan_vien_id', 'id');
    }
}
