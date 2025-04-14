<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class XinNghi extends Model
{
    use HasFactory;

    protected $table = 'xin_nghi';

    protected $fillable = ['nhan_vien_id', 'ngay_xin_nghi', 'ly_do', 'trang_thai', 'nguoi_duyet'];

    public function nhanVien()
    {
        return $this->belongsTo(NhanVien::class, 'nhan_vien_id');
    }

    public function nguoiDuyet()
    {
        return $this->belongsTo(User::class, 'nguoi_duyet');
    }

    public function caLam()
    {
        return $this->hasOne(CaLamNhanVien::class, 'nhan_vien_id', 'nhan_vien_id');
    }
}
