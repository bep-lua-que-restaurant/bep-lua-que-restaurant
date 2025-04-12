<?php

namespace App\Imports;
use App\Models\CaLam;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class CaLamImport implements ToModel, WithHeadingRow
{
    public function headingRow(): int
        {
            return 2; // Bắt đầu từ dòng 2
        }
     //  dd($row);
     public function model(array $row)
     {
         return new CaLam([
            
             'ten_ca' => $row['ten_ca'] ?? '',
             'gio_bat_dau' => $row['gio_bat_dau'] ?? '',
             'gio_ket_thuc' => $row['gio_ket_thuc'] ?? '',
             'deleted_at' => $row['deleted_at'] ?? null,
             'mo_ta' => $row['mo_ta'] ?? null,      
            'created_at' => $row['created_at'] ?? null,      
             'updated_at' => $row['updated_at'] ?? null,      
         ]);
     }
 
 }

