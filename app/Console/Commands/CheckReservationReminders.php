<?php

namespace App\Console\Commands;

use App\Jobs\SendReservationReminderJob;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CheckReservationReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reservations:check-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for reservations that are approaching their time and send reminder emails';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for reservations that need reminders...');

        $now = Carbon::now();
        $reminderIntervals = [
            30 => '30 phút', // 30 minutes before
        ];

        $totalRemindersSent = 0;

        foreach ($reminderIntervals as $minutes => $timeLabel) {
            $targetTime = $now->copy()->addMinutes($minutes);
            
            // Find reservations that are exactly at the reminder time
            $reservations = Reservation::with(['customer', 'restaurant'])
                ->where('status', 'confirmed')
                ->whereBetween('reservation_time', [
                    $targetTime->copy()->subSeconds(5),
                    $targetTime->copy()->addSeconds(5)  
                ])
                ->get();

            foreach ($reservations as $reservation) {
                // Check if we already sent a reminder for this reservation at this interval
                $reminderKey = "reminder_{$reservation->id}_{$minutes}";
                
                if (!cache()->has($reminderKey)) {
                    // Send reminder
                    SendReservationReminderJob::dispatch($reservation, $timeLabel);
                    
                    // Mark as sent to avoid duplicate reminders
                    cache()->put($reminderKey, true, now()->addMinutes($minutes + 1));
                    
                    $totalRemindersSent++;
                    
                    $this->info("Sent {$timeLabel} reminder for reservation #{$reservation->id} to {$reservation->customer->email}");
                    
                    Log::info("Reservation reminder queued: #{$reservation->id} - {$timeLabel} before reservation time");
                }
            }
        }

        if ($totalRemindersSent > 0) {
            $this->info("Total reminders sent: {$totalRemindersSent}");
        } else {
            $this->info('No reminders needed at this time.');
        }

        return 0;
    }
} 