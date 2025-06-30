<?php

namespace App\Jobs;

use App\Mail\ReservationReminderMail;
use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendReservationReminderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $reservation;
    public $timeUntilReservation;

    /**
     * Create a new job instance.
     */
    public function __construct(Reservation $reservation, $timeUntilReservation)
    {
        $this->reservation = $reservation;
        $this->timeUntilReservation = $timeUntilReservation;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Load relationships
        $this->reservation->load(['customer', 'restaurant']);

        // Check if customer and restaurant exist
        if (!$this->reservation->customer || !$this->reservation->restaurant) {
            return;
        }

        // Send reminder email
        Mail::to($this->reservation->customer->email)
            ->send(new ReservationReminderMail(
                $this->reservation,
                $this->reservation->customer,
                $this->reservation->restaurant,
                $this->timeUntilReservation
            ));

        // Log the reminder sent
        Log::info("Reservation reminder sent for reservation #{$this->reservation->id} to {$this->reservation->customer->email}");
    }
} 