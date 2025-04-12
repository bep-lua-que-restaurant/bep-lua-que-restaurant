<?php

namespace App\Imports;

use App\Models\DanhMucMonAn;
use App\Models\MonAn;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class MonAnImport implements ToModel, WithHeadingRow
{
    public function headingRow(): int
        {
            return 2; // Bắt đầu từ dòng 2
        }
     //  dd($row);
     public function model(array $row)
     {
         return new MonAn([
             'danh_muc_mon_an_id' => DanhMucMonAn::where('ten', $row['danh_muc_mon'])->value('id') ?? null,
             'ten' => $row['ten_mon'] ?? '',
             'mo_ta' => $row['mo_ta'] ?? '',
             'gia' => $this->convertGia($row['gia']),
             'created_at' => $row['created_at'] ?? null,      
             'deleted_at' => $row['deleted_at'] ?? null,
         ]);
     }
 
     private function convertGia($gia)
     {
         // Loại bỏ ký tự không phải số
         $gia = preg_replace('/[^0-9,.]/', '', $gia);
 
         // Nếu có dấu phẩy, thay bằng dấu chấm để đúng định dạng số
         $gia = str_replace(',', '', $gia); 
 
         return is_numeric($gia) ? (float) $gia : 0; 
     }
 }

