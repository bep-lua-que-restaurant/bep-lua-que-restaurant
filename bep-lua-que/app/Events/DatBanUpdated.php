<?php

namespace App\Events;

use App\Models\DatBan;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DatBanUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $datBan;

    public function __construct(DatBan $datBan)
    {
        $this->datBan = $datBan;
    }

    public function broadcastOn()
    {
        return new Channel('dat-ban-channel');
    }

    public function broadcastAs()
    {
        return 'dat-ban.updated';
    }
}
