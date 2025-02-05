<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DanhMucMonAn extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'ten',
        'mo_ta',
        'hinh_anh',
    ];
    public function monAns()
    {
        return $this->hasMany(MonAn::class, 'danh_muc_mon_an_id', 'id');
    }
}
