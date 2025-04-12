<?php

namespace App\Exports;

use App\Models\MaGiamGia;
use Maatwebsite\Excel\Concerns\FromCollection;

class MaGiamGiaExport implements FromCollection
{
    /**
     * Trả về collection chứa dữ liệu cần export.
     */
    public function collection()
    {
        return MaGiamGia::all();
    }
}
