<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LichLamViec extends Model
{
    use HasFactory;
    protected $fillable = ['nhan_vien_id', 'ca', 'ngay'];
    
    public function nhanVien()
    {
        return $this->belongsTo(NhanVien::class);
    }
}
