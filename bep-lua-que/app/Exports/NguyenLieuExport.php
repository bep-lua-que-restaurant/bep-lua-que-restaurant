<?php

namespace App\Exports;

use App\Models\NguyenLieu;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class NguyenLieuExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return NguyenLieu::select([
            'ten_nguyen_lieu',
            'loai_nguyen_lieu_id',
            'don_vi_ton',
            'don_gia',
            'so_luong_ton',
            'ghi_chu'
        ])->get();
    }

    public function headings(): array
    {
        return [
            'Tên nguyên liệu',
            'ID loại nguyên liệu',
            'Đơn vị tồn kho',
            'Đơn giá',
            'Số lượng tồn kho',
            'Ghi chú',
        ];
    }
}
