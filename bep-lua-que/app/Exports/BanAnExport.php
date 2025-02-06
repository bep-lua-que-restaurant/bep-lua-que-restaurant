<?php

namespace App\Exports;

use App\Models\BanAn;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class BanAnExport implements FromCollection, WithHeadings, WithEvents, WithMapping
{
    public function collection()
    {
        return BanAn::withTrashed()->select('id', 'ten_ban', 'so_ghe', 'vi_tri', 'created_at', 'deleted_at')->get();
    }

    /**
     * Tiêu đề của file Excel
     */
    public function headings(): array
    {
        return [
            ['Danh Sách Bàn Ăn'], // Tiêu đề lớn
            ['ID', 'Tên Bàn', 'Số Ghế', 'Vị Trí', 'Ngày Tạo', 'Trạng Thái'], // Headers (dòng 2)
        ];
    }

    /**
     * Định dạng dữ liệu xuất ra file
     */
    public function map($row): array
    {
        return [
            $row->id,
            $row->ten_ban,
            $row->so_ghe,
            $row->vi_tri,
            $row->created_at ? $row->created_at->format('d/m/Y') : '',
            $row->deleted_at ? 'Ngừng sử dụng' : 'Đang sử dụng',
        ];
    }

    /**
     * Định dạng giao diện file Excel
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Gộp ô cho tiêu đề lớn
                $sheet->mergeCells('A1:F1');
                $sheet->setCellValue('A1', 'Danh Sách Bàn Ăn');

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

                // Định dạng tiêu đề cột (A2:F2)
                $sheet->getStyle('A2:F2')->applyFromArray([
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
                foreach (range('A', 'F') as $column) {
                    $sheet->getColumnDimension($column)->setAutoSize(true);
                }
            },
        ];
    }
}
