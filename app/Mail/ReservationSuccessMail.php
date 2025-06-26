<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReservationSuccessMail extends Mailable
{
    use Queueable, SerializesModels;

    public $adults;
    public $children;
    public $orderCode;
    public $reservationTime;
    public $tableNumber;

    /**
     * Create a new message instance.
     */
    public function __construct($adults, $children, $orderCode, $reservationTime, $tableNumber)
    {
        $this->adults = $adults;
        $this->children = $children;
        $this->orderCode = $orderCode;
        $this->reservationTime = $reservationTime;
        $this->tableNumber = $tableNumber;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Xác nhận đặt bàn thành công')
                    ->view('emails.reservation_success');
    }
}