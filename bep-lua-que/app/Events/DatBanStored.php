<?php

// app/Events/DatBanStored.php

namespace App\Events;

use App\Models\DatBan;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DatBanStored implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $datBan;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(DatBan $datBan)
    {
        $this->datBan = $datBan;
    }

    /**
     * The channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        // Kênh mà sự kiện sẽ được phát
        return new Channel('datban-channel');
    }

    /**
     * The event's broadcast name.
     *
     * @return string
     */
    public function broadcastAs()
    {
        return 'DatBanStored'; // Tên sự kiện sẽ được lắng nghe trên client
    }
}
