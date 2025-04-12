<?php

namespace App\Imports;

use App\Models\ChucVu;
use App\Models\NhanVien;
use Maatwebsite\Excel\Concerns\ToModel;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\WithHeadingRow;


    
    class NhanVienImport implements ToModel, WithHeadingRow
    {
        public function headingRow(): int
        {
            return 2; // Bắt đầu từ dòng 2
        }
    
        public function model(array $row)
        {
        // dd($row);
            return new NhanVien([
                'chuc_vu_id' => is_numeric($row['chuc_vu']) ? $row['chuc_vu'] : ChucVu::where('ten_chuc_vu', $row['chuc_vu'])->value('id') ?? null,
                'ma_nhan_vien'=> $row['ma_nhan_vien'] ?? null,
                'ho_ten'=> $row['ho_ten'] ?? null,
                'email'=> $row['email'] ?? null,
                'so_dien_thoai'=> $row['so_dien_thoai'] ?? null,
                'password'=> isset($row['mat_khau']) ? bcrypt($row['mat_khau']) : null,
                'dia_chi'=> $row['dia_chi'] ?? null,
                'hinh_anh'=> $row['hinh_anh'] ?? null,
                'gioi_tinh'=> $row['gioi_tinh'] ?? null,
               'ngay_sinh' => !empty($row['ngay_sinh']) ? Carbon::createFromFormat('d/m/Y', $row['ngay_sinh']) : null,
                'ngay_vao_lam' => !empty($row['ngay_vao_lam']) ? Carbon::createFromFormat('d/m/Y', $row['ngay_vao_lam']) : null,
                'deleted_at' => $row['deleted_at'] ?? null,
                'created_at' => $row['created_at'] ?? now(),   
                'updated_at' => $row['updated_at'] ?? now(),
                'trang_thai'=> $row['trang_thai'] ?? 1, // Giả sử mặc định là 1 (đang hoạt động)
            ]);
        }
        
    }


