<?php

namespace App\Exports;

use App\Models\LoaiNguyenLieu;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LoaiNguyenLieuExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return LoaiNguyenLieu::select('id', 'ten_loai', 'ghi_chu')->get();
    }

    public function headings(): array
    {
        return ['ID', 'Tên loại', 'Ghi chú'];
    }
}
