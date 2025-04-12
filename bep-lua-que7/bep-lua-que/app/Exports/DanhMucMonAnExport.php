<?php

namespace App\Exports;

use App\Models\DanhMucMonAn;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Events\AfterSheet;

class DanhMucMonAnExport implements FromCollection, WithHeadings, WithEvents, WithMapping
{
    use Exportable;

    public function collection()
    {
        return DanhMucMonAn::withTrashed()->select('id', 'ten', 'mo_ta','created_at', 'deleted_at')->get();
    }


    public function headings(): array
    {
        return [
            ['Danh Mục Món Ăn'], // Tiêu đề lớn (dòng 1)
            ['ID', 'Tên', 'Mô tả','Ngày tạo', 'Trạng thái kinh doanh'], // Headers (dòng 2)
        ];
    }

    public function map($row): array
{
    return [
        'ID' => $row->id,
        'Tên' => $row->ten,
        'Mô Tả' => $row->mo_ta,
        'Ngày Tạo' => $row->created_at ? $row->created_at->format('d/m/Y') : '',
        'Trạng Thái' => $row->deleted_at ? 'Ngừng kinh doanh' : 'Đang kinh doanh',
    ];
}


    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Gộp ô cho tiêu đề lớn
                $sheet->mergeCells('A1:D1');
                $sheet->setCellValue('A1', 'Danh Mục Món Ăn');

                // Định dạng tiêu đề lớn
                $sheet->getStyle('A1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 16,
                        'color' => ['rgb' => 'FFFFFF'],
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '4F81BD'],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);

                // Định dạng heading
                $sheet->getStyle('A2:D2')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => 'FFFFFF'],
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'FFC000'],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);

                // Tự động căn chỉnh độ rộng cột
                foreach (range('A', 'D') as $column) {
                    $sheet->getColumnDimension($column)->setAutoSize(true);
                }
            },
        ];
    }
}
