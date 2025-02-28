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

class DatBanCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public $datBan;
    public function __construct(DatBan $datBan) // Đảm bảo $datBan là model hợp lệ
    {
        $this->datBan = $datBan;
    }
    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn()
    {
        return [
            new Channel('datban-channel'),
        ];
    }


    public function broadcastWith()
    {
        return [
            'khach_hang_id' => $this->datBan->khach_hang_id,
            'so_dien_thoai'      => $this->datBan->so_dien_thoai,
            'thoi_gian_den'      => $this->datBan->thoi_gian_den,
            'so_nguoi'           => $this->datBan->so_nguoi,
            'trang_thai'         => $this->datBan->trang_thai,
            'ban_an_id'          => $this->datBan->ban_an_id,
            'mo_ta'              => $this->datBan->mo_ta
        ];
    }
}
