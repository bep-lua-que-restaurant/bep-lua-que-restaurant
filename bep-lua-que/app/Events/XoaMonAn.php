<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class XoaMonAn implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $chiTietHoaDon;

    public function __construct($chiTietHoaDon)
    {
        $this->chiTietHoaDon = $chiTietHoaDon;
    }

    public function broadcastOn()
    {
        return new Channel('xoa-mon-an-channel'); // Tên channel
    }

    public function broadcastAs()
    {
        return 'xoa-mon-an-event'; // Tên sự kiện
    }

    public function broadcastWith()
    {
        return [
            'data' =>  $this->chiTietHoaDon->toArray(),
        ];
    }
}
