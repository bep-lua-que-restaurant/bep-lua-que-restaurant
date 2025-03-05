<?php

namespace App\Exports;

use App\Models\NhapKho;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;

class PhieuNhapExport implements FromCollection, WithHeadings, WithMapping, WithEvents
{
    public function collection()
    {
        return NhapKho::withTrashed()
            ->with(['nhanVien', 'nguyenLieu', 'kho'])
            ->select('id', 'ma_nhap_kho', 'nhan_vien_id', 'nguyen_lieu_id', 'kho_id', 'so_luong', 'ngay_nhap', 'trang_thai', 'deleted_at', 'created_at', 'updated_at')
            ->get();
    }

    public function headings(): array
    {
        return [
            ['Danh sách nhập kho'],
            ['ID', 'Mã nhập kho', 'Nhân viên', 'Nguyên liệu', 'Kho', 'Số lượng', 'Ngày nhập', 'Trạng thái', 'Ngày xóa', 'Ngày tạo', 'Ngày cập nhật']
        ];
    }

    public function map($row): array
    {
        // dd($row);
        return [
            'ID' => $row->id,
            'Mã Nhập Kho' => $row->ma_nhap_kho,
            'Nhân Viên' => optional($row->nhanVien)->ho_ten ?? 'Không có dữ liệu',
            'Nguyên Liệu' => optional($row->nguyenLieu)->ten_nguyen_lieu ?? 'Không có dữ liệu',
            'Kho' => optional($row->kho)->ten_kho ?? 'Không có dữ liệu',
            'Số Lượng' => $row->so_luong,
            'Ngày Nhập' => $this->formatDate($row->ngay_nhap),
            'Trạng Thái' => ucfirst(str_replace('_', ' ', $row->trang_thai)),
            'Ngày Xóa' => $this->formatDate($row->deleted_at, 'Không'),
            'Ngày Tạo' => $this->formatDateTime($row->created_at),
            'Ngày Cập Nhật' => $this->formatDateTime($row->updated_at),
        ];
    }
    

/**
 * Định dạng ngày (Date)
 */
    private function formatDate($date, $default = 'N/A')
{
    return $this->isValidDate($date) ? \Carbon\Carbon::parse($date)->format('d/m/Y') : $default;
}

/**
 * Định dạng ngày giờ (DateTime)
 */
    private function formatDateTime($dateTime, $default = 'N/A')
{
    return $this->isValidDate($dateTime) ? \Carbon\Carbon::parse($dateTime)->format('d/m/Y H:i') : $default;
}

/**
 * Kiểm tra xem giá trị có phải là ngày hợp lệ không
 */
private function isValidDate($value)
{
    return !empty($value) && strtotime($value) !== false;
}


    
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Gộp ô cho tiêu đề lớn
                $sheet->mergeCells('A1:K1');
                $sheet->setCellValue('A1', 'Danh sách nhập kho');

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
                $sheet->getStyle('A2:K2')->applyFromArray([
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
                foreach (range('A', 'K') as $column) {
                    $sheet->getColumnDimension($column)->setAutoSize(true);
                }
            },
        ];
    }
}
