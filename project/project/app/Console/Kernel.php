<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{


    protected $commands = [];

    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('update-provider:status')->everyTenMinutes();
        $schedule->command('update-refill:status')->everyTenMinutes();
        $schedule->command('draft-mass-order:delete')->daily();
        $schedule->command('app:auto-currency-update')->daily();
        $schedule->command('app:check-expired-domain')->daily();
        $schedule->command('app:gateway-currency-update')->daily();
        $schedule->command('app:service-price-update')->hourly();
        $schedule->command('model:prune')->days(2);
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
