<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CaLam extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'ten_ca',
        'gio_bat_dau',
        'gio_ket_thuc',
        'mo_ta'
    ];
}
