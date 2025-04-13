<?php

namespace App\Exports;

use App\Models\CaLam;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Events\AfterSheet;

class CaLamExport implements FromCollection, WithHeadings, WithEvents, WithMapping

{
    use Exportable;

    public function collection()
    {
        return CaLam::withTrashed()->select('id', 'ten_ca', 'gio_bat_dau','gio_ket_thuc','deleted_at','mo_ta' ,)->get();
    }


    public function headings(): array
    {
        return [
            ['Ca làm'], // Tiêu đề lớn (dòng 1)
            ['ID', 'Tên ca', 'Giờ bắt đầu','Giờ kết thúc','Trạng thái hoạt động','Mô tả',], // Headers (dòng 2)
        ];
    }

    public function map($row): array
    {
        return [
            $row->id,
            $row->ten_ca,
            $row->gio_bat_dau,
            $row->gio_ket_thuc,
            $row->deleted_at ? 'Ngừng hoạt động' : 'Đang hoạt động', // Trạng thái kinh doanh
            $row->mo_ta,
           
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Gộp ô cho tiêu đề lớn
                $sheet->mergeCells('A1:D1');
                $sheet->setCellValue('A1', 'Ca làm');

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
