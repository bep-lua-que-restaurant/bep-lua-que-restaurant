<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChucVu extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['ten_chuc_vu', 'mo_ta'];

    public function nhanViens()
    {
        return $this->hasMany(NhanVien::class, 'chuc_vu_id');
    }
}
