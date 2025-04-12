<?php

namespace App\Imports;

use App\Models\BanAn;
use Maatwebsite\Excel\Concerns\ToModel;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\WithHeadingRow;


    
    class BanAnImport implements ToModel, WithHeadingRow
    {
        public function headingRow(): int
        {
            return 2; // Bắt đầu từ dòng 2
        }
    
        public function model(array $row)
        {
            
       
            return new BanAn([
                'ten_ban' => $row['ten_ban'] ,
                'so_ghe'=>$row['so_ghe'] ,
                'vi_tri' => $row['vi_tri'] ?? null,
                'mo_ta' => $row['mo_ta'] ?? null,
                'created_at' => $row['created_at'] ?? null,      
                'deleted_at' => $row['deleted_at'] ?? null,
            ]);
        }
    }


