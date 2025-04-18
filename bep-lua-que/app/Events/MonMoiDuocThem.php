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
                'ten' => optional($monAn->monAn)->ten ?? 'Không xác định', // 👈 Tránh null
                'thoi_gian_nau' => optional($monAn->monAn)->thoi_gian_nau,
                'ban' => optional($monAn->hoaDon)->banAns->pluck('ten_ban')->join(', '),
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
                    'ban' => $monAn['ban'],
                    'so_luong' => $monAn['so_luong'],
                    'ghi_chu' => $monAn['ghi_chu'],
                ];
            })
        ];
    }
}
