<?php

namespace App\Imports;

use App\Models\DichVu;
use Maatwebsite\Excel\Concerns\ToModel;

class DichVuImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    
    public function model(array $row)
    {
      

    return new DichVu([
        'ten_dich_vu' => $row[0],  // Cột 1 trong Excel ánh xạ vào 'ten_dich_vu'
        'mo_ta' => $row[1]         // Cột 0 trong Excel ánh xạ vào 'mo_ta'
    ]);
    }
}
