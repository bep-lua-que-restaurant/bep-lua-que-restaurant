<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class NhanVienSeeder extends Seeder
{
    public function run()
    {
        DB::table('nhan_viens')->insert([
            [
                'chuc_vu_id' => 4, // Admin
                'ma_nhan_vien' => 'NV0024',
                'ho_ten' => 'Nguyễn Văn A',
                'email' => 'admin@gmail.com',
                'so_dien_thoai' => '0912345678',
                'password' => Hash::make('12345678'), // Mã hóa mật khẩu
                'dia_chi' => 'Địa chỉ Admin',
                'hinh_anh' => 'default.png',
                'gioi_tinh' => 'nam',
                'ngay_sinh' => '1990-01-01',
                'ngay_vao_lam' => '2020-01-01',
                'trang_thai' => 'dang_lam_viec',
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ],
            [
                'chuc_vu_id' => 3, // Thu ngân
                'ma_nhan_vien' => 'NV0035',
                'ho_ten' => 'Trần Thị B',
                'email' => 'thungan@gmail.com',
                'so_dien_thoai' => '0912345679',
                'password' => Hash::make('12345678'), // Mã hóa mật khẩu
                'dia_chi' => 'Địa chỉ Thu ngân',
                'hinh_anh' => 'default.png',
                'gioi_tinh' => 'nu',
                'ngay_sinh' => '1995-02-15',
                'ngay_vao_lam' => '2021-03-10',
                'trang_thai' => 'dang_lam_viec',
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ],
            [
                'chuc_vu_id' => 2, // Bếp
                'ma_nhan_vien' => 'NV0076',
                'ho_ten' => 'Lê Văn C',
                'email' => 'bep@gmail.com',
                'so_dien_thoai' => '0912345680',
                'password' => Hash::make('12345678'), // Mã hóa mật khẩu
                'dia_chi' => 'Địa chỉ Bếp',
                'hinh_anh' => 'default.png',
                'gioi_tinh' => 'nam',
                'ngay_sinh' => '1988-11-20',
                'ngay_vao_lam' => '2019-07-15',
                'trang_thai' => 'dang_lam_viec',
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ],
            [
                'chuc_vu_id' => 1, 
                'ma_nhan_vien' => 'NV00726',
                'ho_ten' => 'Lê Văn k',
                'email' => 'letan@gmail.com',
                'so_dien_thoai' => '0962345680',
                'password' => Hash::make('12345678'), // Mã hóa mật khẩu
                'dia_chi' => 'Địa chỉ le tan',
                'hinh_anh' => 'default.png',
                'gioi_tinh' => 'nam',
                'ngay_sinh' => '1988-11-20',
                'ngay_vao_lam' => '2019-07-15',
                'trang_thai' => 'dang_lam_viec',
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ]
        ]);
    }
}
