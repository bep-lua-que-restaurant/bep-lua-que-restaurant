<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HinhAnhMonAn extends Model
{
    use HasFactory;
    protected $table = 'hinh_anh_mon_ans';
    protected $fillable = [
        'mon_an_id',
        'hinh_anh',
    ];

    public function monAn()
    {
        return $this->belongsTo(MonAn::class, 'mon_an_id');
    }
}
