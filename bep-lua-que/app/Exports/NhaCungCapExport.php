<?php

namespace App\Exports;

use App\Models\DanhMucMonAn;
use App\Models\NhaCungCap;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Events\AfterSheet;

class NhaCungCapExport implements FromCollection, WithHeadings, WithEvents, WithMapping
{
    use Exportable;

    public function collection()
    {
        return NhaCungCap::withTrashed()->select('ten_nha_cung_cap', 'dia_chi', 'so_dien_thoai', 'email', 'moTa', 'created_at', 'deleted_at')->get();
    }


    public function headings(): array
    {
        return [
            ['Nhà Cung Cấp'], // Tiêu đề lớn (dòng 1)
            ['Tên', 'Địa chỉ', 'Số điện thoại', 'Email', 'Mô tả'], // Headers (dòng 2)
        ];
    }

    public function map($row): array
    {
        return [
            $row->ten_nha_cung_cap,
            $row->dia_chi,
            $row->so_dien_thoai,
            $row->email,
            $row->moTa,
//            $row->created_at ? $row->created_at->format('d/m/Y') : '',
//            $row->deleted_at ? 'Ngừng hoạt động' : 'Đang hoạt động', // Trạng thái kinh doanh
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Gộp ô cho tiêu đề lớn
                $sheet->mergeCells('A1:E1');
                $sheet->setCellValue('A1', 'Nhà Cung Cấp');

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
                $sheet->getStyle('A2:E2')->applyFromArray([
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
                foreach (range('A', 'E') as $column) {
                    $sheet->getColumnDimension($column)->setAutoSize(true);
                }
            },
        ];
    }
}
