<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Broadcasting\InteractsWithSockets;


class DatBanDeleted implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $maDatBan;

    public function __construct($maDatBan)
    {
        $this->maDatBan = $maDatBan;
    }

    public function broadcastOn()
    {
        return new Channel('datban-channel');
    }

    public function broadcastWith()
    {
        return ['maDatBan' => $this->maDatBan];
    }
}
