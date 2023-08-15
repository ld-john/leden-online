<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('cleanVehicle')->everyFiveMinutes();
        //            ->dailyAt('23:50');
        $schedule
            ->command('checkReservationExpiry')
            ->weekdays()
            ->dailyAt('23:55')
            ->withoutOverlapping()
            ->runInBackground();
        $schedule
            ->command('checkDeliveries')
            ->weekdays()
            ->dailyAt('9:30')
            ->withoutOverlapping()
            ->runInBackground();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
