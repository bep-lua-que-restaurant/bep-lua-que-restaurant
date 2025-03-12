<?php

namespace App\Imports;

use App\Models\NhaCungCap;
use Maatwebsite\Excel\Concerns\ToModel;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\WithHeadingRow;


    
    class NhaCungCapImport implements ToModel, WithHeadingRow
    {
        public function headingRow(): int
        {
            return 2; // Bắt đầu từ dòng 2
        }
    
        public function model(array $row)
        {
        // dd($row);
       
            return new NhaCungCap([
                'ten_nha_cung_cap' => $row['ten'] ,
                'created_at' => $row['created_at'] ?? null,      
                'deleted_at' => $row['deleted_at'] ?? null,
            ]);
        }
    }


