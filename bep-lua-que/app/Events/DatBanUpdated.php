<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\DatBan;

class DatBanUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $danhSachBan;

    public function __construct($danhSachBan)
    {
        $this->danhSachBan = $danhSachBan;
    }

    public function broadcastOn()
    {
        return new Channel('datban-channel');
    }

    public function broadcastWith()
    {
        return [
            'danh_sach_ban' => $this->danhSachBan
        ];
    }
}
