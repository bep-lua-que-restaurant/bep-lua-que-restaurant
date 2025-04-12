<?php

namespace App\Imports;

use App\Models\ChucVu;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;


class ChucVuImport implements ToModel,WithHeadingRow
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
            'mo_ta' => $row['mo_ta'] ?? null,
            'created_at' => isset($row['created_at']) ? \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['created_at']) : null,
            'deleted_at' => ($row['trang_thai_hoat_dong'] ?? '') === 'Ngừng hoạt động' ? now() : null,
        ]);
    }
}
