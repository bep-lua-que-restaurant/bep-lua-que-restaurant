<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NhaCungCap extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'ten_nha_cung_cap',
        'dia_chi',
        'so_dien_thoai',
        'email',
        'ghi_chu',
        'hinhAnh',
        'moTa'
    ];

    public function phieuNhapKhos()
    {
        return $this->hasMany(PhieuNhapKho::class, 'nha_cung_cap_id');
    }

    public function phieuXuatKhos()
    {
        return $this->hasMany(PhieuXuatKho::class, 'nha_cung_cap_id');
    }

}
