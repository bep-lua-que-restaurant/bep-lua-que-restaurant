<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonBiHuy extends Model
{
    use HasFactory;

    // Các thuộc tính không còn sử dụng sẽ bị xóa
    protected $table = 'mon_bi_huys';

    // Cập nhật mảng $fillable để loại bỏ các cột đã xóa
    protected $fillable = [
        'mon_an_id',
        'ly_do',
        'so_luong',
        'ngay_huy',
        // 'danh_muc_mon_an_id', // Bỏ cột này
        // 'hinh_anh',           // Bỏ cột này
        // 'don_gia',            // Bỏ cột này
    ];

    // Nếu bạn không cần `timestamps` (created_at, updated_at) thì có thể tắt chúng
    public $timestamps = true;

    // Nếu bạn sử dụng `date` cho cột ngày hủy
    protected $dates = ['ngay_huy'];
}
