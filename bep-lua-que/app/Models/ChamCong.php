<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChamCong extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'cham_congs';

    protected $fillable = [
        'nhan_vien_id',
        'ngay_cham_cong',
        'gio_vao_lam',
        'gio_ket_thuc',
        'mo_ta',
    ];

    //Liên kết với model Nhân viên
    public function nhanVien(){
        return $this->belongsTo(NhanVien::class, 'nhan_vien_id');
    }
}
