<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MonAn extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable=[
        'danh_muc_mon_an_id',
        'ten',
        'mo_ta',
        'gia',
        'trang_thai',   

    ];
     /**
     * Quan hệ với bảng DanhMucMonAn
     */
    public function danhMuc()
    {
        return $this->belongsTo(DanhMucMonAn::class, 'danh_muc_mon_an_id', 'id');
    }

    /**
     * Quan hệ với bảng HinhAnhMonAn
     */
    public function hinhAnhs()
    {
        return $this->hasMany(HinhAnhMonAn::class, 'mon_an_id', 'id');
    }
}
