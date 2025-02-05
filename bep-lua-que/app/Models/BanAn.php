<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BanAn extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['ten_ban', 'so_ghe', 'mo_ta', 'vi_tri'];
}
