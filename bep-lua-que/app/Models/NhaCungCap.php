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
        'so_dien_thoai',
        'email',
        'dia_chi',
        'mo_ta',
    ];

    /**
     * Một nhà cung cấp có thể có nhiều phiếu nhập kho.
     */
    public function phieuNhapKhos()
    {
        return $this->hasMany(PhieuNhapKho::class, 'nha_cung_cap_id');
    }
}
