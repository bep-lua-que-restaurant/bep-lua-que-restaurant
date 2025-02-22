<?php

namespace App\Events;

use App\Models\ChiTietHoaDon;
use Illuminate\Support\Facades\Log;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class MonMoiDuocThem implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $monAn;

    public function __construct(ChiTietHoaDon $monAn)
    {
        $this->monAn = $monAn->load('monAn', 'hoaDon.banAns'); // Load quan hệ để client có đầy đủ thông tin
    }

    public function broadcastOn()
    {

        return ['bep-channel']; // Kênh giao diện bếp lắng nghe
    }

    public function broadcastAs()
    {
        return 'mon-moi-duoc-them'; // Tên sự kiện cho frontend
    }

    public function broadcastWith()
    {
        Log::info('Đã broadcast sự kiện MonMoiDuocThem', ['monAn' => $this->monAn]);

        return [
            'monAn' => $this->monAn
        ];
    }
}
