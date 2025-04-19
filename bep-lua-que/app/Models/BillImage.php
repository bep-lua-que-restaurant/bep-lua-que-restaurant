<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillImage extends Model
{
    use HasFactory;

    // Bảng liên kết
    protected $table = 'bill_images';

    // Các cột có thể gán giá trị hàng loạt (mass assignable)
    protected $fillable = [
        'hoa_don_id',   // Khóa ngoại liên kết với hóa đơn
        'image_path',   // Đường dẫn ảnh bill
    ];

    // Quan hệ với bảng HoaDon
    public function hoaDon()
    {
        return $this->belongsTo(HoaDon::class, 'hoa_don_id');
    }
}
