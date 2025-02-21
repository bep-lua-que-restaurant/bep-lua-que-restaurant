<?php

namespace App\Events;

use App\Models\ChiTietHoaDon;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class TrangThaiCapNhat implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $monAn;

    public function __construct(ChiTietHoaDon $monAn)
    {
        $this->monAn = $monAn;
    }

    public function broadcastOn()
    {
        return new Channel('bep-channel'); // Tên channel
    }

    public function broadcastAs()
    {
        return 'trang-thai-cap-nhat'; // Tên sự kiện
    }
}
