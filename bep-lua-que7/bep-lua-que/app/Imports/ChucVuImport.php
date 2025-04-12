<?php

namespace App\Imports;

use App\Models\ChucVu;
use Maatwebsite\Excel\Concerns\ToModel;

class ChucVuImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    
    public function headingRow(): int
        {
            return 2; // Bắt đầu từ dòng 2
        }
    
        public function model(array $row)
        {
            
       
            return new ChucVu([
                'ten_chuc_vu' => $row['ten_chuc_vu'] ,
                'mo_ta' => $row['mo_ta'] ,
                'created_at' => $row['created_at'] ?? null,      
                'deleted_at' => $row['deleted_at'] ?? null,
            ]);
        }
}
