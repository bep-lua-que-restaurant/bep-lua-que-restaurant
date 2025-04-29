<?php

namespace App\Exports;

use App\Models\MonAn;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;

class MonAnExport implements FromCollection, WithHeadings, WithMapping, WithEvents
{
    public function collection()
    {
        return MonAn::withTrashed()
            ->with('danhMuc') // Eager load danh mục
            ->select('id', 'danh_muc_mon_an_id', 'ten', 'mo_ta', 'gia', 'trang_thai', 'created_at', 'deleted_at','thoi_gian_nau')
            ->get();
    }

    public function headings(): array
    {
        return [
            ['Món ăn'],
            ['ID', 'Danh mục món', 'Tên món', 'Mô tả', 'Giá', 'Trạng thái', 'Ngày tạo', 'Tình trạng','Thời gian nấu']
        ];
    }

    public function map($row): array
    {
        return [
            'ID' => $row->id,
            'Danh Mục' => optional($row->danhMuc)->ten ?? 'Không có danh mục',
            'Tên' => $row->ten,
            'Mô Tả' => $row->mo_ta,
            'Giá' => number_format($row->gia, 0, ',', '.') . ' đ',
            'Trạng Thái' => ucfirst(str_replace('_', ' ', $row->trang_thai)),
            'Ngày Tạo' => $row->created_at ? $row->created_at->format('d/m/Y') : 'Không có',
            'Trạng Thái Kinh Doanh' => $row->deleted_at ? 'Ngừng kinh doanh' : 'Đang kinh doanh',
            'Thời gian nấu' => $row->thoi_gian_nau ,
        ];
    }
    
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Gộp ô cho tiêu đề lớn
                $sheet->mergeCells('A1:D1');
                $sheet->setCellValue('A1', 'Món ăn');

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
