<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\MonAn;

class ThucDonUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $monAn;
    public function __construct(MonAn $monAn)
    {
        $this->monAn = $monAn;
    }

    public function broadcastOn()
    {
        return new Channel('thucdon-channel'); // Channel riêng cho thực đơn
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->monAn->id,
            'ten' => $this->monAn->ten,
            'gia' => $this->monAn->gia,
            'trang_thai' => $this->monAn->trang_thai,
        ];
    }
}
