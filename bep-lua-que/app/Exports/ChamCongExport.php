<?php

namespace App\Exports;

use App\Models\ChamCong;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class ChamCongExport implements FromCollection, WithHeadings, WithMapping, WithEvents
{
    public function collection()
    {
        return ChamCong::withTrashed()
            ->with(['nhanVien:id,ho_ten', 'caLam:id,ten_ca'])
            ->select('id', 'nhan_vien_id', 'ca_lam_id', 'ngay_cham_cong', 'mo_ta', 'deleted_at', 'created_at', 'updated_at')
            ->get();
    }

    public function headings(): array
    {
        return [
            ['Bảng chấm công'], // Tiêu đề lớn
            ['ID', 'Tên nhân viên', 'Tên ca làm', 'Ngày chấm công', 'Mô tả', 'Ngày xóa', 'Ngày tạo', 'Ngày cập nhật'],
        ];
    }

    public function map($chamCong): array
    {
        return [
            $chamCong->id,
            $chamCong->nhanVien->ho_ten ?? 'N/A',
            $chamCong->caLam->ten_ca ?? 'N/A',
            $chamCong->ngay_cham_cong,
            $chamCong->mo_ta,
            $chamCong->deleted_at,
            $chamCong->created_at,
            $chamCong->updated_at,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Gộp ô tiêu đề lớn
                $sheet->mergeCells('A1:H1');
                $sheet->setCellValue('A1', 'Bảng chấm công');

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

                // Định dạng hàng tiêu đề
                $sheet->getStyle('A2:H2')->applyFromArray([
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
                foreach (range('A', 'H') as $column) {
                    $sheet->getColumnDimension($column)->setAutoSize(true);
                }
            },
        ];
    }
}