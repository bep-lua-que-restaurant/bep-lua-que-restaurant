<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HoaDonBanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [];

        for ($i = 0; $i < 1000; $i++) {
            $randomDate = Carbon::now()->subDays(rand(0, 730));

            $data[] = [

                'ban_an_id' => rand(76, 95), // Bàn từ 1 đến 20
                'hoa_don_id' => rand(3105, 4104), // Hóa đơn giả định từ 1091 đến 2100

                'trang_thai' => 'da_thanh_toan',
                'deleted_at' => null,
                'created_at' => $randomDate,
                'updated_at' => $randomDate,
            ];
        }

        DB::table('hoa_don_bans')->insert($data);
    }
}
