<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReservationReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public $reservation;
    public $customer;
    public $restaurant;
    public $timeUntilReservation;

    /**
     * Create a new message instance.
     */
    public function __construct($reservation, $customer, $restaurant, $timeUntilReservation)
    {
        $this->reservation = $reservation;
        $this->customer = $customer;
        $this->restaurant = $restaurant;
        $this->timeUntilReservation = $timeUntilReservation;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Nhắc nhở: Đơn đặt bàn sắp đến giờ')
                    ->view('emails.reservation_reminder');
    }
} 