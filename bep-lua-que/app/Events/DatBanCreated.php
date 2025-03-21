<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\KhachHang;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Broadcasting\InteractsWithSockets;


class DatBanCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $danhSachBanDat;
    public $customer;

    public function __construct(array $danhSachBanDat, KhachHang $customer)
    {
        $this->danhSachBanDat = $danhSachBanDat;
        $this->customer = $customer;
    }

    public function broadcastOn()
    {
        return new Channel('datban-channel');
    }

    public function broadcastWith()
    {
        return [
            'danh_sach_ban' => collect($this->danhSachBanDat)->map(function ($datBan) {
                return [
                    'ban_an_id' => $datBan->ban_an_id,
                    'thoi_gian_den' => $datBan->thoi_gian_den,
                    'gio_du_kien' => $datBan->gio_du_kien,
                    'so_nguoi' => $datBan->so_nguoi,
                    'trang_thai' => $datBan->trang_thai,
                    'ma_dat_ban' => $datBan->ma_dat_ban,
                    'datban_id' => $datBan->id,
                ];
            }),
            'customer' => [
                'id' => $this->customer->id,
                'ten' => $this->customer->ten,
                'so_dien_thoai' => $this->customer->so_dien_thoai,
                'email' => $this->customer->email ?? null,
            ]
        ];
    }
}
