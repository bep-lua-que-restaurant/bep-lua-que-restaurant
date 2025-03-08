<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class MonMoiDuocThem implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $monAn;

    public function __construct($monAn)
    {
        $this->monAn = $monAn;
        $this->monAn = [
            'id' => $this->monAn->id,  // Thêm id vào sự kiện
            'ten' => $this->monAn->monAn->ten,
            'ban' => $this->monAn->hoaDon->banAns->pluck('ten_ban')->join(', '),
            'so_luong' => $this->monAn->so_luong,
        ];
    }

    public function broadcastOn()
    {
        return new Channel("bep-channel");  // 👈 Nếu dùng private channel thì sửa thành PrivateChannel
    }

    public function broadcastAs()
    {
        return "mon-moi-duoc-them";  // 👈 Đảm bảo frontend lắng nghe đúng sự kiện này
    }

    public function broadcastWith()
{
    // Tải quan hệ monAn, hoaDon (bao gồm banAns và chiTietHoaDon)
    // $this->monAn->load('hoaDon.banAns', 'hoaDon.chiTietHoaDon'); 
    
    return [
        'monAn' => $this->monAn, // Trả về món ăn đầy đủ thông tin
    ];
}

}
