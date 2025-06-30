<?php

namespace App\Jobs;

use App\Mail\ReservationCancellationMail;
use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendReservationCancellationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $reservation;
    public $cancellationTime;

    /**
     * Create a new job instance.
     */
    public function __construct(Reservation $reservation, $cancellationTime = null)
    {
        $this->reservation = $reservation;
        $this->cancellationTime = $cancellationTime;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            // Load relationships
            $this->reservation->load(['customer', 'restaurant']);
            
            // Send cancellation email
            Mail::to($this->reservation->customer->email)
                ->send(new ReservationCancellationMail($this->reservation, $this->cancellationTime));
            
            Log::info("Reservation cancellation email sent successfully", [
                'reservation_id' => $this->reservation->id,
                'customer_email' => $this->reservation->customer->email,
                'cancellation_time' => $this->cancellationTime
            ]);
            
        } catch (\Exception $e) {
            Log::error("Failed to send reservation cancellation email", [
                'reservation_id' => $this->reservation->id,
                'error' => $e->getMessage()
            ]);
            
            throw $e;
        }
    }
} 