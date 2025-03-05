<?php

namespace App\Imports;

use App\Models\ComBo;
use Maatwebsite\Excel\Concerns\ToModel;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\WithHeadingRow;


    
    class ComboImport implements ToModel, WithHeadingRow
    {
        public function headingRow(): int
        {
            return 2; // Bắt đầu từ dòng 2
        }
    
        public function model(array $row)
        {
       
            return new ComBo([
                'ten' => $row['ten'] ,
                'mo_ta' => $row['mo_ta'] ?? '',
                'hinh_anh' => $row['hinh_anh'] ?? '',
                'deleted_at' => $row['deleted_at'] ?? null,
            ]);
        }
    }


