<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\BanAn;

class BanAnUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $banAn;
    public $deleted;
    public function __construct(BanAn $banAn)
    {
        $this->banAn = $banAn; // Chỉ gửi dữ liệu của bàn ăn bị thay đổi
        $this->deleted = $banAn->deleted_at ? true : false; // Kiểm tra trạng thái xóa
    }

    public function broadcastOn()
    {
        return new Channel('banan-channel');
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
