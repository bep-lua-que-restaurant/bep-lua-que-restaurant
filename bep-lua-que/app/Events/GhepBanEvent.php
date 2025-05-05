<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class GhepBanEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $monAns;
    public $maHoaDon;
    public $maHoaDonCu;

    /**
     * Tạo một instance sự kiện mới.
     *
     * @param array $monAns Danh sách các món ăn với ma_hoa_don đã được cập nhật
     * @param string $maHoaDon Mã hóa đơn thống nhất
     * @param array $maHoaDonCu Danh sách các mã hóa đơn cũ từ các bàn được ghép
     */
    public function __construct(array $monAns, string $maHoaDon, array $maHoaDonCu)
    {
        $this->monAns = $monAns;
        $this->maHoaDon = $maHoaDon;
        $this->maHoaDonCu = $maHoaDonCu;
    }

    /**
     * Lấy kênh mà sự kiện sẽ phát trên đó.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('ghep-channel');
    }

    /**
     * Lấy tên sự kiện.
     *
     * @return string
     */
    public function broadcastAs()
    {
        return 'ghep-ban';
    }
}