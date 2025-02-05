<?php

namespace App\Exports;

use App\Models\MonAn;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class MonAnExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return MonAn::withTrashed()->with('danhMuc')->get();
    }

    public function headings(): array
    {
        return [
            'Tên',
            'Mô tả',
            'Giá',
            'Danh mục',
            'Trạng thái',
            'Ngày tạo',
            'Trạng thái kinh doanh',
        ];
    }

    public function map($row): array
    {
        return [
            $row->ten,
            $row->mo_ta,
            $row->gia,
            $row->danhMuc->ten ?? 'Không có danh mục',
            ucfirst(str_replace('_', ' ', $row->trang_thai)),
            $row->created_at->format('d/m/Y'),
            $row->deleted_at ? 'Ngừng kinh doanh' : 'Đang kinh doanh',
        ];
    }
}
