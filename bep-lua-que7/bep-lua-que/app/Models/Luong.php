<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;

class Luong extends Model
{
    use HasFactory;
    // use SoftDeletes;
    protected $fillable = ['nhan_vien_id', 'hinh_thuc', 'muc_luong'];

    public function nhanVien()
    {
        return $this->belongsTo(NhanVien::class);
    }
}
