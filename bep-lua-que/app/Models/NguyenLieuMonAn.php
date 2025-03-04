<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NguyenLieuMonAn extends Model
{
    use HasFactory;

    protected $table = 'nguyen_lieu_mon_ans';

    protected $fillable = ['mon_an_id', 'nguyen_lieu_id', 'so_luong', 'don_vi_tinh'];

    public function monAn()
    {
        return $this->belongsTo(MonAn::class, 'mon_an_id');
    }

    public function nguyenLieu()
    {
        return $this->belongsTo(NguyenLieu::class, 'nguyen_lieu_id');
    }
}

