<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;


class MonMoiDuocThem implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $monAns;

    public function __construct($monAns)
    {
        $this->monAns = collect($monAns)->map(function ($monAn) {
            return [
                'id' => $monAn->id,
                'ten' => optional($monAn->monAn)->ten ?? 'Không xác định',
                'thoi_gian_nau' => optional($monAn->monAn)->thoi_gian_nau,
                'ma_hoa_don' => optional($monAn->hoaDon)->ma_hoa_don ?? 'Không có mã hóa đơn', // Lấy mã hóa đơn
                'so_luong' => $monAn->so_luong,
                'ghi_chu' => $monAn->ghi_chu,
            ];
        });
    }

    public function broadcastOn()
    {
        return new Channel("bep-channel");
    }

    public function broadcastAs()
    {
        return "mon-moi-duoc-them";
    }


    public function broadcastWith()
    {
        return [
            'monAns' => $this->monAns->map(function ($monAn) {
                return [
                    'id' => $monAn['id'],
                    'ten' => $monAn['ten'],
                    'thoi_gian_nau' => $monAn['thoi_gian_nau'],
                    'ma_hoa_don' => $monAn['ma_hoa_don'], // Trả về mã hóa đơn
                    'so_luong' => $monAn['so_luong'],
                    'ghi_chu' => $monAn['ghi_chu'],
                ];
            })->toArray() // Chuyển thành mảng
        ];
    }
}
