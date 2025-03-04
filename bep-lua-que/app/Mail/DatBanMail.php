<?php

namespace App\Mail;

use App\Models\KhachHang;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DatBanMail extends Mailable
// class DatBanMail extends Mailable implements ShouldQueue

{
    use Queueable, SerializesModels;

    public $datBan;

    public $customer;
    public $danhSachBanDat;

    /**
     * Create a new message instance.
     */

    public function __construct(KhachHang $customer, $danhSachBanDat)
    {
        $this->customer = $customer;
        $this->danhSachBanDat = $danhSachBanDat;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Xác nhận đặt bàn thành công',
        );
    }

    /**
     * Get the message content definition.
     */

    public function content(): Content
    {
        return new Content(
            view: 'emails.dat_ban',
            with: [
                'customer' => $this->customer,
                'danhSachBanDat' => $this->danhSachBanDat,
            ],
        );
    }
}
