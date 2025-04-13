<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;



class LichSuLuong extends Model
{
    use HasFactory;

    protected $table = 'lich_su_luong';

    protected $fillable = ['id_nhan_vien', 'luong', 'thang', 'nam'];

    public function nhanVien()
    {
        return $this->belongsTo(NhanVien::class, 'id_nhan_vien');
    }
}
