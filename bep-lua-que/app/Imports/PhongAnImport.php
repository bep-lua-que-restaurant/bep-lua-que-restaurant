<?php

namespace App\Imports;

use App\Models\PhongAn;
use Maatwebsite\Excel\Concerns\ToModel;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\WithHeadingRow;


    
    class PhongAnImport implements ToModel, WithHeadingRow
    {
        public function headingRow(): int
        {
            return 2; // Bắt đầu từ dòng 2
        }
    
        public function model(array $row)
        {
            
       
            return new PhongAn([
                'ten_phong_an' => $row['ten_phong_an'] ,
                'created_at' => $row['created_at'] ?? null,      
                'deleted_at' => $row['deleted_at'] ?? null,
            ]);
        }
    }


