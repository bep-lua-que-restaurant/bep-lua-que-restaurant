<?php

namespace App\Listeners;

use App\Events\DatBanDeleted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\DatBan;

class HandleDatBanDeleted
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(DatBanDeleted $event)
    {
        // Xử lý xóa dữ liệu ở đây
        DatBan::where('ma_dat_ban', $event->maDatBan)->delete();
    }
}
