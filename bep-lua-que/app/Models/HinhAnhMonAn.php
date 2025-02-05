<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HinhAnhMonAn extends Model
{
    use HasFactory;
    protected $fillable=[
        'mon_an_id',
        'hinh_anh',
    ];
}
