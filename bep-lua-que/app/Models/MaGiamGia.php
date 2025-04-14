<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;
class MaGiamGia extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'ma_giam_gias';

    protected $fillable = [
        'code', 'type', 'value', 'min_order_value', 'start_date', 'end_date', 'usage_limit'
    ];
}
