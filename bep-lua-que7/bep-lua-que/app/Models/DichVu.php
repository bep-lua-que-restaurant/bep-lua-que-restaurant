<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DichVu extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = [
        'ten_dich_vu',
        'mo_ta',
    ];

}
