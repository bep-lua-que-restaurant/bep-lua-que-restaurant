<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KhachHang extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['ho_ten', 'email', 'so_dien_thoai', 'dia_chi', 'can_cuoc'];
}
