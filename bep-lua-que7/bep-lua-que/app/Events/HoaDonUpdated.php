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

    public function __construct(HoaDon $hoaDon)
    {
        $this->hoaDon = $hoaDon->load('chiTietHoaDons');
    }

    public function broadcastOn()
    {
        return new Channel('hoa-don-channel'); // Kênh broadcast cho hóa đơn
    }

    public function broadcastWith()
    {
        return [
            'type' => 'hoa_don_updated', // Đánh dấu loại sự kiện
            'hoa_don' => $this->hoaDon->toArray(),
        ];
    }
}
