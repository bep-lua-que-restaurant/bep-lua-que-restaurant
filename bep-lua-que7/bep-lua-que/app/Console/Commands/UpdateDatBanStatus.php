<?php

namespace App\Console\Commands;

use App\Models\DatBan;
use Carbon\Carbon;
use Illuminate\Console\Command;


class UpdateDatBanStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'datban:update-status';
    // protected $signature = 'app:update-dat-ban-status';

    /**
     * The console command description.
     *
     * @var string
     */
    // protected $description = 'Command description';
    protected $description = 'Cập nhật trạng thái đơn đặt bàn thành "da_huy" nếu thoi_gian_den muộn hơn hiện tại 30 phút';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Lấy tất cả các đơn đặt bàn có trạng thái 'dang_xu_ly'
        $datBans = DatBan::where('trang_thai', 'dang_xu_ly')
            ->where('thoi_gian_den', '<', Carbon::now()->subMinutes(30))
            ->get();

        // Cập nhật trạng thái của các đơn đặt bàn này
        foreach ($datBans as $datBan) {
            $datBan->update(['trang_thai' => 'da_huy']);
            $this->info("Đặt bàn ID {$datBan->id} đã được cập nhật thành 'da_huy'");
        }
    }
}
