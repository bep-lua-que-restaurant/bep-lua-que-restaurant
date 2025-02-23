<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CaLamNhanVien extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'ca_lam_nhan_viens';
    protected $fillable = ['ca_lam_id', 'nhan_vien_id', 'ngay_lam'];

    public function nhanVien(){
        return $this->belongsTo(NhanVien::class,'nhan_vien_id');
    }
}
