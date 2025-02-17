<?php

namespace App\Events;

use App\Models\HoaDon;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class HoaDonUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $hoaDon;
    public $deleted;
    public function __construct(HoaDon $hoaDon)
    {
        $this->hoaDon = $hoaDon; // Dữ liệu hóa đơn bị thay đổi
        $this->deleted = true; // Trạng thái xóa hóa đơn
    }

    public function broadcastOn()
    {
        return new Channel('hoa-don-channel'); // Kênh broadcast cho hóa đơn
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->hoaDon->id,
            'ma_hoa_don' => $this->hoaDon->ma_hoa_don,
            'tong_tien' => $this->hoaDon->tong_tien,
            'phuong_thuc_thanh_toan' => $this->hoaDon->phuong_thuc_thanh_toan,
            'deleted' => $this->deleted
        ];
    }
}
