<?php

namespace App\Exports;

use App\Models\MaGiamGia;
use Maatwebsite\Excel\Concerns\FromCollection;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use Carbon\Carbon;

class MaGiamGiaExport implements FromCollection,WithHeadings, WithEvents, WithMapping
{
    /**
     * Trả về collection chứa dữ liệu cần export.
     */
    public function collection()
    {
        return MaGiamGia::withTrashed()->select('id', 'code','type','value', 'min_order_value','start_date','end_date','usage_limit','created_at', 'deleted_at')->get();
    }

    public function headings(): array
    {
        return [
            ['Mã giảm giá'], // Tiêu đề lớn (dòng 1)
            ['ID', 'Mã','Loại', 'Giá trị','Đơn tối thiểu' ,'Ngày bắt đầu','Ngày kết thúc','Số lượt đã dùng','Ngày tạo','Trạng thái hoạt động'], // Headers (dòng 2)
        ];
    }

public function map($row): array
{
    return [
        $row->id,
        $row->code,
        $row->type === 'percentage' ? 'Phần trăm' : 'Tiền',
        $row->value,
        $row->min_order_value,
        $row->start_date ? $row->start_date : '',
        $row->end_date ? $row->end_date : '',

        $row->usage_limit,
        $row->created_at ? Carbon::parse($row->created_at)->format('d/m/Y') : '',
        $row->deleted_at ? 'Ngừng hoạt động' : 'Đang hoạt động', // Trạng thái kinh doanh
    ];
}

    

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Gộp ô cho tiêu đề lớn
                $sheet->mergeCells('A1:J1');
                $sheet->setCellValue('A1', 'Mã giảm giá');

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
                $sheet->getStyle('A2:J2')->applyFromArray([
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
                foreach (range('A', 'J') as $column) {
                    $sheet->getColumnDimension($column)->setAutoSize(true);
                }
            },
        ];
    }
}
