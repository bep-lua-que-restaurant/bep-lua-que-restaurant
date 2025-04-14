<?php
<<<<<<< HEAD

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;
class MaGiamGia extends Model
{
    use HasFactory;

    protected $table = 'ma_giam_gias';

    protected $fillable = [
        'code', 'type', 'value', 'min_order_value', 'start_date', 'end_date', 'usage_limit'
    ];
}
=======
 
 namespace App\Models;
 
 use Illuminate\Database\Eloquent\Factories\HasFactory;
 use Illuminate\Database\Eloquent\Model;
 
 use Illuminate\Database\Eloquent\SoftDeletes;
 class MaGiamGia extends Model
 {
     use HasFactory;
 
     protected $table = 'ma_giam_gias';
 
     protected $fillable = [
        'mã_giảm_giá',
        'loại',
        'giá_trị',
        'giá_trị_đơn_hàng_tối_thiểu',
        'ngày_bắt_đầu',
        'ngày_kết_thúc',
        'giới_hạn_sử_dụng',
     ];
 }
>>>>>>> eb0fe4acf6f066edf0be422cb1177add1f22f2ba
