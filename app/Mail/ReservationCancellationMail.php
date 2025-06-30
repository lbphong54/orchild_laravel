<?php

namespace App\Mail;

use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;

class ReservationCancellationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $reservation;
    public $cancellationTime;
    public $isRefundable;
    public $refundMessage;

    /**
     * Create a new message instance.
     */
    public function __construct(Reservation $reservation, $cancellationTime = null)
    {
        $this->reservation = $reservation;
        $this->cancellationTime = $cancellationTime ?? Carbon::now();
        
        // Kiểm tra xem có được hoàn tiền hay không
        $hoursBeforeReservation = $this->cancellationTime->diffInHours($reservation->reservation_time, false);
        $this->isRefundable = $hoursBeforeReservation >= 1;
        
        if ($this->isRefundable) {
            $this->refundMessage = "Đơn hàng của bạn sẽ được hoàn tiền trong vòng 3-5 ngày làm việc.";
        } else {
            $this->refundMessage = "Do hủy đơn trong vòng 1 giờ trước thời gian đặt bàn, đơn hàng không được hoàn tiền theo chính sách của chúng tôi.";
        }
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Thông báo hủy đơn đặt bàn')
                    ->view('emails.reservation_cancellation');
    }
} 