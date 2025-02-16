<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LoaiNguyenLieu extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = [
        'ma_loai',
        'ten_loai',
        'mo_ta',
    ];

    public function nguyenLieus()
    {
        return $this->hasMany(NguyenLieu::class, 'loai_nguyen_lieu_id');
    }
}
