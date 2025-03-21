<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\BanAn;

class BanAnUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public BanAn $banAn;
    public bool $deleted;

    public function __construct(BanAn $banAn)
    {
        $this->banAn = $banAn;
        $this->deleted = $banAn->deleted_at !== null; // Kiểm tra trạng thái xóa mềm
    }

    public function broadcastOn()
    {
        return new Channel('banan-channel'); // Chuyển sang kênh private nếu cần bảo mật
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->banAn->id,
            'ten_ban' => $this->banAn->ten_ban,
            'trang_thai' => $this->banAn->trang_thai,
            'deleted' => $this->deleted
        ];
    }
}
