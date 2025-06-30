<?php

namespace App\Console\Commands;

use App\Jobs\SendReservationCancellationJob;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Console\Command;

class TestReservationCancellation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reservations:test-cancellation {--reservation-id=} {--hours-before=1}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test sending reservation cancellation emails';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $reservationId = $this->option('reservation-id');
        $hoursBefore = (int) $this->option('hours-before');

        if ($reservationId) {
            // Test specific reservation
            $reservation = Reservation::with(['customer', 'restaurant'])->find($reservationId);
            
            if (!$reservation) {
                $this->error("Reservation #{$reservationId} not found!");
                return 1;
            }

            $this->info("Testing cancellation for reservation #{$reservation->id}");
            $this->info("Customer: {$reservation->customer->email}");
            $this->info("Restaurant: {$reservation->restaurant->name}");
            $this->info("Reservation time: {$reservation->reservation_time}");
            
            // Simulate cancellation time
            $cancellationTime = $reservation->reservation_time->copy()->subHours($hoursBefore);
            $this->info("Simulated cancellation time: {$cancellationTime}");
            
            $hoursBeforeReservation = $cancellationTime->diffInHours($reservation->reservation_time, false);
            $isRefundable = $hoursBeforeReservation >= 1;
            
            $this->info("Hours before reservation: {$hoursBeforeReservation}");
            $this->info("Is refundable: " . ($isRefundable ? 'Yes' : 'No'));

            // Send test cancellation email
            SendReservationCancellationJob::dispatch($reservation, $cancellationTime);
            
            $this->info("Test cancellation email sent successfully!");
        } else {
            // Show available reservations for testing
            $reservations = Reservation::with(['customer', 'restaurant'])
                ->where('status', '!=', 'cancelled')
                ->where('reservation_time', '>', now())
                ->orderBy('reservation_time')
                ->limit(10)
                ->get();

            if ($reservations->isEmpty()) {
                $this->error('No reservations available for testing!');
                return 1;
            }

            $this->info('Available reservations for testing:');
            $this->table(
                ['ID', 'Customer', 'Restaurant', 'Reservation Time', 'Status'],
                $reservations->map(function ($reservation) {
                    return [
                        $reservation->id,
                        $reservation->customer->email,
                        $reservation->restaurant->name,
                        $reservation->reservation_time->format('Y-m-d H:i'),
                        $reservation->status
                    ];
                })
            );

            $this->info("\nTo test a specific reservation, use:");
            $this->info("php artisan reservations:test-cancellation --reservation-id=<ID> --hours-before=<HOURS>");
        }

        return 0;
    }
} 