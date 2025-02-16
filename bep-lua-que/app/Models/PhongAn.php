<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class PhongAn extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['ten_phong_an'];
    // PhongAn model
    public function banAns()
    {
        return $this->hasMany(BanAn::class, 'vi_tri');  // 'vi_tri' là khóa ngoại trong bảng BanAn
    }
}
