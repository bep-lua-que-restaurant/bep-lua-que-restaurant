<?php

namespace Database\Seeders;

use App\Models\HoaDon;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class HoaDonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 0; $i < 1000; $i++) {
            $randomDate = Carbon::now()->subDays(rand(0, 730));
            DB::table('hoa_dons')->insert([
                'ma_hoa_don' => 'HD-' . now()->format('Ymd') . '-' . Str::random(4),
                'ma_dat_ban' => null,
                'tong_tien' => rand(40000, 150000),
                'phuong_thuc_thanh_toan' => 'tien_mat',
                'mo_ta' => null,
                'deleted_at' => null,
                'created_at' => $randomDate,
                'updated_at' => $randomDate,
            ]);
        }
    }
}
