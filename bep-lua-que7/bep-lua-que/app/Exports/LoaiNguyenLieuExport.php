<?php

namespace App\Exports;

use App\Models\LoaiNguyenLieu;
use Maatwebsite\Excel\Concerns\FromCollection;

class LoaiNguyenLieuExport implements FromCollection
{
    public function collection()
    {
        return LoaiNguyenLieu::all(['ma_loai', 'ten_loai', 'mo_ta']);
    }
}
