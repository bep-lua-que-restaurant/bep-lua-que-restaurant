<?php

namespace App\Listeners;

use App\Events\DatBanCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Mail\DatBanMail;
use Illuminate\Support\Facades\Mail;

class GuiEmailDatBan implements ShouldQueue
{
    use InteractsWithQueue;

    public function __construct()
    {
        //
    }

    public function handle(DatBanCreated $event): void
    {
        $customer = $event->customer; // Khách hàng đặt bàn
        $danhSachBanDat = $event->danhSachBanDat; // Danh sách bàn đã đặt

        if ($customer && $customer->email) {
            Mail::to($customer->email)->queue(new DatBanMail($customer, $danhSachBanDat));
        }
    }
}
