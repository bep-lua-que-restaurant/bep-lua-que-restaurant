<?php

namespace App\Events;

use App\Models\HoaDon;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class HoaDonAdded implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $hoaDon;

    public function __construct(HoaDon $hoaDon)
    {
        $this->hoaDon = $hoaDon->load('chiTietHoaDons');
    }

    public function broadcastOn()
    {
        return new Channel('hoa-don-channel'); // Dùng chung kênh
    }

    public function broadcastWith()
    {
        return [
            'type' => 'hoa_don_added', // Đánh dấu loại sự kiện
            'hoa_don' => $this->hoaDon->toArray(),
        ];
    }
}
