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

    public $customer;
    public $danhSachBanDat;

    public function __construct(KhachHang $customer, array $danhSachBanDat)
    {
        $this->customer = $customer;
        $this->danhSachBanDat = $danhSachBanDat;
    }

    public function broadcastOn()
    {
        return new Channel('datban-channel');
    }

    public function broadcastWith()
    {
        return [
            'khach_hang' => [
                'id' => $this->customer->id,
                'ho_ten' => $this->customer->ho_ten,
                'so_dien_thoai' => $this->customer->so_dien_thoai,
            ],
            'danh_sach_ban' => collect($this->danhSachBanDat)->map(function ($datBan) {
                return [
                    'ban_an_id' => $datBan->ban_an_id,
                    'thoi_gian_den' => $datBan->thoi_gian_den,
                    'gio_du_kien' => $datBan->gio_du_kien,
                    'so_nguoi' => $datBan->so_nguoi,
                    'trang_thai' => $datBan->trang_thai,
                    'ma_dat_ban' => $datBan->ma_dat_ban, // Thêm mã đặt bàn
                    'datban_id' => $datBan->id, // Thêm ID đặt bàn
                ];
            }),
        ];
    }
}
