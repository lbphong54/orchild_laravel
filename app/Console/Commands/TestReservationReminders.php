<?php

namespace App\Console\Commands;

use App\Jobs\SendReservationReminderJob;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Console\Command;

class TestReservationReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reservations:test-reminders {--reservation-id=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test sending reservation reminder emails';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $reservationId = $this->option('reservation-id');

        if ($reservationId) {
            // Test specific reservation
            $reservation = Reservation::with(['customer', 'restaurant'])->find($reservationId);
            
            if (!$reservation) {
                $this->error("Reservation #{$reservationId} not found!");
                return 1;
            }

            $this->info("Testing reminder for reservation #{$reservation->id}");
            $this->info("Customer: {$reservation->customer->email}");
            $this->info("Restaurant: {$reservation->restaurant->name}");
            $this->info("Reservation time: {$reservation->reservation_time}");

            // Send test reminder
            SendReservationReminderJob::dispatch($reservation, 'TEST - 30 phút');
            
            $this->info("Test reminder sent successfully!");
        } else {
            // Show available reservations for testing
            $reservations = Reservation::with(['customer', 'restaurant'])
                ->where('status', 'confirmed')
                ->where('reservation_time', '>', now())
                ->orderBy('reservation_time')
                ->limit(10)
                ->get();

            if ($reservations->isEmpty()) {
                $this->error("No confirmed reservations found!");
                return 1;
            }

            $this->info("Available reservations for testing:");
            $this->table(
                ['ID', 'Customer', 'Restaurant', 'Time', 'Status'],
                $reservations->map(function ($reservation) {
                    return [
                        $reservation->id,
                        $reservation->customer->email ?? 'N/A',
                        $reservation->restaurant->name ?? 'N/A',
                        $reservation->reservation_time->format('Y-m-d H:i'),
                        $reservation->status
                    ];
                })
            );

            $this->info("\nTo test a specific reservation, use:");
            $this->info("php artisan reservations:test-reminders --reservation-id=<ID>");
        }

        return 0;
    }
} 