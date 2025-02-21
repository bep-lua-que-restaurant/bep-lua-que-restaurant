<?php

namespace App\Imports;

use App\Models\BanAn;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Events\AfterImport;

class BanAnImport implements ToModel, WithHeadingRow, WithValidation, WithEvents
{
    use SkipsFailures;  // Sử dụng trait SkipsFailures để bỏ qua các lỗi khi import

    /**
     * Chuyển đổi mỗi dòng của file Excel thành một model BanAn
     */
    public function model(array $row)
    {
        static $processed = []; // Lưu các tên bàn đã xử lý

        if (in_array($row['ten_ban'], $processed)) {
            return null; // Bỏ qua nếu đã xử lý trước đó
        }

        $processed[] = $row['ten_ban']; // Đánh dấu đã xử lý

        return new BanAn([
            'ten_ban'   => $row['ten_ban'],
            'so_ghe'    => $row['so_ghe'],
            'vi_tri'    => $row['vi_tri'],
            'created_at' => now(),
        ]);
    }


    /**
     * Định nghĩa quy tắc validate cho từng dòng dữ liệu trong Excel
     */
    public function rules(): array
    {
        return [
            'ten_ban' => 'required|string',
            'so_ghe'  => 'required|integer',
            'vi_tri'  => 'nullable|string',
        ];
    }

    /**
     * Sự kiện sau khi nhập dữ liệu thành công
     */
    public function registerEvents(): array
    {
        return [
            AfterImport::class => function (AfterImport $event) {
                // Thực hiện một số hành động sau khi nhập dữ liệu thành công nếu cần
            },
        ];
    }
}
