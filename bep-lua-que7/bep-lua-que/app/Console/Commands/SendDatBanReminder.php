<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Models\DatBan;
use App\Mail\DatBanMail;

class SendDatBanReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    // protected $signature = 'app:send-dat-ban-reminder';
    protected $signature = 'datban:send-reminder';


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gửi email nhắc nhở khách trước 1 tiếng';


    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now();
        $notifyTime = $now->addHour(); // Lấy danh sách đặt bàn có thời gian đến trong 1 giờ tới

        // Lấy danh sách đặt bàn cần gửi email nhắc nhở, sắp xếp theo khách hàng và thời gian tạo
        $datBanList = DatBan::where('thoi_gian_den', '<=', $notifyTime)
            ->where('thoi_gian_den', '>', $now)
            ->orderBy('khach_hang_id')
            ->orderBy('created_at')
            ->get();

        // Gom nhóm các đặt bàn có thông tin giống nhau và thời gian gần nhau
        $groupedDatBans = [];
        foreach ($datBanList as $datBan) {
            $groupKey = $datBan->khach_hang_id . '|' . $datBan->so_dien_thoai . '|' . $datBan->so_nguoi . '|' . $datBan->trang_thai . '|' . $datBan->mo_ta;

            if (!isset($groupedDatBans[$groupKey])) {
                $groupedDatBans[$groupKey] = [];
            }

            // Chỉ thêm đơn nếu nó được tạo trong vòng 2 phút so với nhóm
            if (empty($groupedDatBans[$groupKey]) || $datBan->created_at->diffInMinutes(end($groupedDatBans[$groupKey])->created_at) <= 2) {
                $groupedDatBans[$groupKey][] = $datBan;
            }
        }

        // Gửi email cho từng nhóm đặt bàn
        foreach ($groupedDatBans as $group) {
            $firstDatBan = $group[0]; // Lấy thông tin chung của nhóm
            $customer = $firstDatBan->khachHang;
            $danhSachBanDat = $group; // Danh sách bàn đã gom nhóm

            if ($customer && $customer->email) {
                Mail::to($customer->email)->send(new DatBanMail($customer, $danhSachBanDat));
                $this->info('Đã gửi email nhắc nhở cho: ' . $customer->email);
            }
        }
    }
}
